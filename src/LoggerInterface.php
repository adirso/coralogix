<?php

namespace Adirsolomon\CoralogixPackage;

interface LoggerInterface
{
    /**
     * @param string $log
     * @return void
     */
    public function log(string $log): void;
}