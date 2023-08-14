<?php

namespace Adirsolomon\CoralogixPackage;

use Guzzle\Http\Client;
use Illuminate\Support\ServiceProvider;

class LoggerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLogger();
    }

    private function registerLogger()
    {
        $this->app
            ->singleton(ClientAPI::class, function ($app) {
                return new ClientAPI(new Client(), env('CORALOGIX_PRIVATE_KEY'));
            });

        $this->app
            ->singleton(Logger::class, function ($app) {
                return new Logger($app[ClientAPI::class], env('APP_NAME'), env('SUBSYSTEM_NAME'));
            });
    }
}