<?php

namespace CalcTek\Calculator\Evaluator\Evaluators;

use CalcTek\Calculator\Evaluator\EvaluationException;
use CalcTek\Calculator\Parser\Nodes\SyntaxNode;
use CalcTek\Calculator\Parser\Nodes\UnaryExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Operator;

class UnaryExpressionSyntaxNodeEvaluator extends SyntaxNodeEvaluator
{
    /**
     * @inheritdoc
     */
    public function canEvaluate(SyntaxNode $node): bool
    {
        return is_a($node, UnaryExpressionSyntaxNode::class);
    }

    /**
     * @inheritdoc
     */
    public function evaluate(SyntaxNode $node): float
    {
        /* @var UnaryExpressionSyntaxNode $node */
        $operator = $node->getOperator();
        $operand = $node->getOperand();
        $operandValue = $this->evaluator->evaluate($operand);

        return match ($operator) {
            Operator::Minus => -$operandValue,
            default => throw new EvaluationException("Unary expressions shouldn't have any other operator than -")
        };
    }
}
