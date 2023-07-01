<?php

namespace CalcTek\Calculator\Contracts;

use CalcTek\Calculator\Evaluator\EvaluationException;
use CalcTek\Calculator\Lexer\LexingException;
use CalcTek\Calculator\Parser\SyntaxException;

interface CalculatorService
{
    /**
     * Evaluate the input of the given input string
     *
     * @param string $input The input to calculate
     * @return float The result of the calculation
     * @throws EvaluationException If the input is invalid at evaluation, i.e. sqrt(-1)
     * @throws SyntaxException If the input is syntactically invalid
     * @throws LexingException If the input is lexically invalid
     */
    public function calculate(string $input): float;
}
