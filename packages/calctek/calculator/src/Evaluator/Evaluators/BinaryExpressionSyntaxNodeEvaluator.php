<?php

namespace CalcTek\Calculator\Evaluator\Evaluators;

use CalcTek\Calculator\Evaluator\EvaluationException;
use CalcTek\Calculator\Parser\Nodes\BinaryExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\SyntaxNode;
use CalcTek\Calculator\Parser\Operator;

class BinaryExpressionSyntaxNodeEvaluator extends SyntaxNodeEvaluator
{
    /**
     * @inheritdoc
     */
    public function canEvaluate(SyntaxNode $node): bool
    {
        return is_a($node, BinaryExpressionSyntaxNode::class);
    }

    /**
     * @inheritdoc
     */
    public function evaluate(SyntaxNode $node): float
    {
        /* @var BinaryExpressionSyntaxNode $node */
        $operator = $node->getOperator();
        $left = $node->getLeft();
        $right = $node->getRight();
        $leftValue = $this->evaluator->evaluate($left);
        $rightValue = $this->evaluator->evaluate($right);

        return match ($operator) {
            Operator::Plus => $leftValue + $rightValue,
            Operator::Minus => $leftValue - $rightValue,
            Operator::Multiply => $leftValue * $rightValue,
            Operator::Divide => $leftValue / $rightValue,
            Operator::Power => $leftValue ** $rightValue,
            default => throw new EvaluationException("Unknown operator: $operator")
        };
    }
}
