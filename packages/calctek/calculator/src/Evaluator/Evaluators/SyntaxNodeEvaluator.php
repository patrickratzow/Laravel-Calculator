<?php

namespace CalcTek\Calculator\Evaluator\Evaluators;

use CalcTek\Calculator\Parser\Nodes\LiteralSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\SyntaxNode;

abstract class SyntaxNodeEvaluator
{
    abstract public function canEvaluate(SyntaxNode $node): bool;
    abstract public function evaluate(SyntaxNode $node): float;
}
