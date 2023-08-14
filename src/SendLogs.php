<?php

namespace Adirsolomon\CoralogixPackage;

use Carbon\Carbon;
use Illuminate\Console\Command;

class sendLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        parent::__construct();
        $this->logger = $logger;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = Carbon::today();
        $file = sprintf("laravel-%s.log", $date->format('Y-m-d'));
        $path = sprintf("%s/logs", storage_path());

        if (file_exists(sprintf("%s/%s", $path, $file))) {
            $newName = $this->getNewName();
            $this->changeFileName($file, $newName, $path);

            $handle = fopen(sprintf("%s/in_process/%s", $path, $newName), "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    $this->logger->log($line);
                }

                fclose($handle);
            }
            $this->completeWithLogs($path, $newName);
        }
    }

    /**
     * @param string $file
     * @param string $newName
     * @param string $path
     * @return void
     */
    public function changeFileName(string $file, string $newName, string $path): void
    {
        rename(sprintf("%s/%s", $path, $file), sprintf("%s/in_process/%s", $path, $newName));
    }

    /**
     * @return string
     */
    public function getNewName(): string
    {
        return sprintf("%s.log", md5(date("Y-m-d H:i:s")));
    }

    /**
     * @param string $path
     * @param string $fileName
     * @return void
     */
    public function completeWithLogs(string $path, string $fileName): void
    {
        rename(sprintf("%s/in_process/%s", $path, $fileName), sprintf("%s/finished/%s", $path, $fileName));
    }
}