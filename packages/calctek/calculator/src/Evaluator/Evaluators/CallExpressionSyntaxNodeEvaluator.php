<?php

namespace CalcTek\Calculator\Evaluator\Evaluators;

use CalcTek\Calculator\Evaluator\EvaluationException;
use CalcTek\Calculator\Parser\Functions;
use CalcTek\Calculator\Parser\Nodes\CallExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\SyntaxNode;

class CallExpressionSyntaxNodeEvaluator extends SyntaxNodeEvaluator
{
    /**
     * @inheritdoc
     */
    public function canEvaluate(SyntaxNode $node): bool
    {
        return is_a($node, CallExpressionSyntaxNode::class);
    }

    /**
     * @inheritdoc
     */
    public function evaluate(SyntaxNode $node): float
    {
        /* @var CallExpressionSyntaxNode $node */
        $functionName = $node->getIdentifier()->getName();
        $function = Functions::from($functionName);
        $argument = $node->getArgument();
        $argumentValue = $this->evaluator->evaluate($argument);

        return match ($function) {
            Functions::SquareRoot => $this->squareRoot($argumentValue),
            default => throw new EvaluationException("Unknown function: $functionName")
        };
    }

    /**
     * @inheritdoc
     */
    private function squareRoot(float $value): float
    {
        if ($value < 0) {
            throw new EvaluationException("Cannot take the square root of a negative number.");
        }

        return sqrt($value);
    }
}
