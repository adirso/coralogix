<?php

namespace Adirsolomon\CoralogixPackage;

enum Severity: int
{
    case Debug = 1;
    case Verbose = 2;
    case Info = 3;
    case Warn = 4;
    case Error = 5;
    case Critical = 6;
}