<?php

namespace CalcTek\Calculator\Evaluator\Evaluators;

use CalcTek\Calculator\Evaluator\EvaluationException;
use CalcTek\Calculator\Evaluator\Evaluator;
use CalcTek\Calculator\Parser\Nodes\SyntaxNode;

abstract class SyntaxNodeEvaluator
{
    /**
     * @var Evaluator $evaluator The evaluator that will be used to evaluate the child nodes
     */
    protected Evaluator $evaluator;

    /**
     * @param Evaluator $evaluator The evaluator that will be used to evaluate the child nodes
     */
    public function setEvaluator(Evaluator $evaluator): void
    {
        $this->evaluator = $evaluator;
    }

    /**
     * Determines whether the node can be evaluated
     *
     * @param SyntaxNode $node The node to check
     * @return bool Whether the node can be evaluated
     */
    abstract public function canEvaluate(SyntaxNode $node): bool;

    /**
     * @param SyntaxNode $node The node to evaluate
     * @return float The result of the evaluation
     * @throws EvaluationException If the node cannot be evaluated
     */
    abstract public function evaluate(SyntaxNode $node): float;
}
