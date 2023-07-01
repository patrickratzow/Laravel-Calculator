<?php

namespace CalcTek\Calculator\Tests\Feature\Evaluator\Evaluators;

use CalcTek\Calculator\Evaluator\Evaluator;
use CalcTek\Calculator\Evaluator\Evaluators\UnaryExpressionSyntaxNodeEvaluator;
use CalcTek\Calculator\Parser\Nodes\LiteralSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\UnaryExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Operator;
use Orchestra\Testbench\TestCase;

class UnaryExpressionSyntaxNodeEvaluatorTest extends TestCase
{
    /** @test */
    public function it_can_evaluate_a_unary_expression_syntax_node()
    {
        // Arrange
        $node = new UnaryExpressionSyntaxNode(
            Operator::Minus,
            new LiteralSyntaxNode('2')
        );
        $evaluator = new UnaryExpressionSyntaxNodeEvaluator();
        $evaluator->setEvaluator(new Evaluator());

        // Act
        $result = $evaluator->evaluate($node);

        // Assert
        $this->assertEquals(-2, $result);
    }
}
