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
            Operator::Divide => $this->divide($leftValue, $rightValue),
            Operator::Power => $leftValue ** $rightValue,
            default => throw new EvaluationException("Unknown operator: $operator")
        };
    }

    private function divide(float $left, float $right): float
    {
        // Check for division by zero
        // Be aware of floating point precision issues
        if (abs($right) < PHP_FLOAT_EPSILON) {
            throw new EvaluationException("Cannot divide by zero.");
        }

        return $left / $right;
    }
}
