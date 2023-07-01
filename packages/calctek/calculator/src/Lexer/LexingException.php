<?php

namespace CalcTek\Calculator\Lexer;

use Exception;
use Throwable;

class LexingException extends Exception
{
    /**
     * @inheritdoc
     */
    public function __construct($message, $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
