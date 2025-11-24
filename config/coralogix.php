<?php

return [
    'private_key' => env('CORALOGIX_PRIVATE_KEY'),
    'application_name' => env('CORALOGIX_APPLICATION', env('APP_NAME')),
    'subsystem_name' => env('CORALOGIX_SUBSYSTEM'),
    'endpoint' => env('CORALOGIX_ENDPOINT', 'https://ingress.coralogix.com/api/v1/logs'),
];
