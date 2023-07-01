<?php

namespace CalcTek\Calculator\Evaluator\Evaluators;

use CalcTek\Calculator\Parser\Nodes\LiteralSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\SyntaxNode;

class LiteralSyntaxNodeEvaluator extends SyntaxNodeEvaluator
{
    /**
     * @inheritdoc
     */
    public function canEvaluate(SyntaxNode $node): bool
    {
        return is_a($node, LiteralSyntaxNode::class);
    }

    /**
     * @inheritdoc
     */
    public function evaluate(SyntaxNode $node): float
    {
        /* @var LiteralSyntaxNode $node */
        return (float)$node->getValue();
    }
}
