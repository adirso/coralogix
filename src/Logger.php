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
     * @var string
     */
    private string $endpoint;

    /**
     * @param ClientAPI $clientAPI
     * @param string $applicationName
     * @param string|null $subsystemName
     * @param string $endpoint
     */
    public function __construct(ClientAPI $clientAPI, string $applicationName, ?string $subsystemName, string $endpoint)
    {
        $this->clientAPI = $clientAPI;
        $this->applicationName = $applicationName;
        $this->subsystemName = $subsystemName;
        $this->endpoint = $endpoint;
    }

    /**
     * @param string $log
     * @return void
     */
    public function log(string $log): void
    {
        $decoded = json_decode($log, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $this->addLog($this->transformArrayToLog($decoded));
            return;
        }

        $this->addLog(new Log(Carbon::now(), Severity::Info->value, trim($log)));
    }

    /**
     * @param Log $log
     * @return void
     */
    private function addLog(Log $log): void
    {
        $this->clientAPI->addLog($log, $this->getApplicationName(), $this->getSubSystemName(), $this->endpoint);
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
    private function transformArrayToLog(array $log): Log
    {
        $timestamp = isset($log['datetime']) ? Carbon::createFromTimeString($log['datetime']) : Carbon::now();
        $severity = isset($log['level']) ? $this->getSeverity((int)$log['level']) : Severity::Info->value;
        $message = $log['message'] ?? json_encode($log);

        return new Log($timestamp, $severity, $message);
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
