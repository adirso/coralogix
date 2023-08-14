<?php

namespace Adirsolomon\CoralogixPackage;

use Carbon\Carbon;

class Logger implements LoggerInterface
{
    /**
     * @var ClientAPI
     */
    private ClientAPI $clientAPI;

    /**
     * @var string
     */
    private string $applicationName;

    /**
     * @var string|null
     */
    private ?string $subsystemName;

    /**
     * @param ClientAPI $clientAPI
     * @param string $applicationName
     * @param string|null $subsystemName
     */
    public function __construct(ClientAPI $clientAPI, string $applicationName, ?string $subsystemName)
    {
        $this->clientAPI = $clientAPI;
        $this->applicationName = $applicationName;
        $this->subsystemName = $subsystemName;
    }

    /**
     * @param string $log
     * @return void
     */
    public function log(string $log): void
    {
        $message = json_decode($log, 1);
        $this->addLog($this->transformToLog($message));
    }

    /**
     * @param Log $log
     * @return void
     */
    private function addLog(Log $log): void
    {
        $this->clientAPI->addLog($log, $this->getApplicationName(), $this->getSubSystemName());
    }

    /**
     * @return string
     */
    private function getApplicationName(): string
    {
        return $this->applicationName;
    }

    /**
     * @return string
     */
    private function getSubSystemName(): string
    {
        return $this->subsystemName ?? "";
    }

    /**
     * @param array $log
     * @return Log
     */
    private function transformToLog(array $log): Log
    {
        return new Log(Carbon::createFromTimeString($log['datetime']), $this->getSeverity($log['level']), $log['message']);
    }

    /**
     * @param int $level
     * @return int
     */
    private function getSeverity(int $level): int
    {
        if ($level == 200 || $level == 250) {
            return Severity::Info->value;
        }

        if ($level == 400) {
            return Severity::Error->value;
        }

        if ($level == 550 || $level == 300) {
            return Severity::Warn->value;
        }

        if ($level == 500 || $level == 600) {
            return Severity::Critical->value;
        }

        return Severity::Debug->value;
    }
}
