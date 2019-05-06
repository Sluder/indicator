<?php

namespace Sluder\Indicator\Exceptions;

use Exception;

class TrendException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}