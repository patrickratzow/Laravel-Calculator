<?php

namespace CalcTek\Calculator\Evaluator;

use CalcTek\Calculator\Parser\Nodes\SyntaxNode;

class Evaluator
{
    /**
     * @var array<> $evaluators The evaluators for each node type
     */
    private static $evaluators = [];

    public function evaluate(SyntaxNode $abstractSyntaxTree): float
    {

        return 0;
    }
}
