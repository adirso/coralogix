<?php

namespace Adirsolomon\CoralogixPackage;

use Guzzle\Http\Client;

class ClientAPI
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * @var string
     */
    private string $privateKey;

    /**
     * @param Client $client
     * @param string $privateKey
     */
    public function __construct(Client $client, string $privateKey)
    {
        $this->client = $client;
        $this->privateKey = $privateKey;
    }

    public function addLog(Log $log, string $applicationName, string $subSystemName): void
    {
        try {
            $options = [
                "headers" => [
                    "Content-Type" => "application/json",
                ],
                "json" => [
                    "privateKey" => $this->privateKey,
                    "applicationName" => $applicationName,
                    "subsystemName" => $subSystemName,
                    "logEntries" =>
                        [
                            [
                                "timestamp" => $log->getTime()->unix(),
                                "hiResTimestamp" => (string)$log->getTime()->unix(),
                                "severity" => $log->getSeverity(),
                                "text" => $log->getText(),
                                "category" => $log->getCategory() ?? "DAL",
                                "className" => $log->getClassName() ?? "UserManager",
                                "methodName" => $log->getMethodName() ?? "RegisterUser",
                                "threadId" => $log->getThreadId() ?? "a-352",
                            ],
                        ]
                ]
            ];

            $this->client->post("https://ingress.coralogix.com/api/v1/logs", $options);
        } catch (\Exception $e) {
            // TODO: Add what to do.
        }
    }
}
