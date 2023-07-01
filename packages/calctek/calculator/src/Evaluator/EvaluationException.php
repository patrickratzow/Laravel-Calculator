<?php

namespace CalcTek\Calculator\Evaluator;

use Exception;
use Throwable;

class EvaluationException extends Exception
{
    /**
     * @inheritdoc
     */
    public function __construct($message, $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
