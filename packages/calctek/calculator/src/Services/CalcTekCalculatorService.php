<?php

namespace CalcTek\Calculator\Services;

use CalcTek\Calculator\Contracts\CalculatorService;
use CalcTek\Calculator\Evaluator\Evaluator;
use CalcTek\Calculator\Lexer\Lexer;
use CalcTek\Calculator\Parser\Parser;

class CalcTekCalculatorService implements CalculatorService
{
    /**
     * @inheritdoc
     */
    public function calculate(string $input): float
    {
        $lexer = new Lexer();
        $tokens = $lexer->tokenize($input);

        $parser = new Parser($tokens);
        $parser->parse();
        $ast = $parser->getAST();

        $evaluator = new Evaluator();
        return $evaluator->evaluate($ast);
    }
}
