<?php

namespace CalcTek\Calculator\Parser;

use Throwable;

class SyntaxException extends \Exception
{
    public function __construct($message, $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
