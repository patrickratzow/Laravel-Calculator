<?php

namespace CalcTek\Calculator\Evaluator;

use CalcTek\Calculator\Evaluator\Evaluators\BinaryExpressionSyntaxNodeEvaluator;
use CalcTek\Calculator\Evaluator\Evaluators\CallExpressionSyntaxNodeEvaluator;
use CalcTek\Calculator\Evaluator\Evaluators\LiteralSyntaxNodeEvaluator;
use CalcTek\Calculator\Evaluator\Evaluators\UnaryExpressionSyntaxNodeEvaluator;
use CalcTek\Calculator\Parser\Nodes\SyntaxNode;

class Evaluator
{
    /**
     * @var array<string> $evaluators The evaluators for each node type
     */
    private static array $evaluators = [
        LiteralSyntaxNodeEvaluator::class,
        BinaryExpressionSyntaxNodeEvaluator::class,
        UnaryExpressionSyntaxNodeEvaluator::class,
        CallExpressionSyntaxNodeEvaluator::class,
    ];

    /**
     * Evaluates the abstract syntax tree
     *
     * @param SyntaxNode $abstractSyntaxTree The abstract syntax tree to evaluate
     * @return float The result of the evaluation
     * @throws EvaluationException If no evaluator is found for a node type
     */
    public function evaluate(SyntaxNode $abstractSyntaxTree): float
    {
        foreach (self::$evaluators as $evaluator) {
            $evaluator = new $evaluator();
            $evaluator->setEvaluator($this);

            if ($evaluator->canEvaluate($abstractSyntaxTree)) {
                return $evaluator->evaluate($abstractSyntaxTree);
            }
        }

        throw new EvaluationException("No evaluator found for node type: " . get_class($abstractSyntaxTree));
    }
}
