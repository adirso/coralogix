<?php

namespace Adirsolomon\CoralogixPackage;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class LoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/coralogix.php' => config_path('coralogix.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                SendLogs::class,
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/coralogix.php', 'coralogix');
        $this->registerLogger();
    }

    private function registerLogger()
    {
        $this->app
            ->singleton(ClientAPI::class, function ($app) {
                return new ClientAPI(new Client(), config('coralogix.private_key'));
            });

        $this->app
            ->singleton(Logger::class, function ($app) {
                return new Logger(
                    $app[ClientAPI::class],
                    config('coralogix.application_name', config('app.name')),
                    config('coralogix.subsystem_name'),
                    config('coralogix.endpoint')
                );
            });
    }
}