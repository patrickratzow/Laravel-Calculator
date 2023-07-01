<?php

namespace CalcTek\Calculator\Tests\Feature\Evaluator\Evaluators;

use CalcTek\Calculator\Evaluator\EvaluationException;
use CalcTek\Calculator\Evaluator\Evaluator;
use CalcTek\Calculator\Evaluator\Evaluators\CallExpressionSyntaxNodeEvaluator;
use CalcTek\Calculator\Parser\Functions;
use CalcTek\Calculator\Parser\Nodes\CallExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\IdentifierSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\LiteralSyntaxNode;
use Orchestra\Testbench\TestCase;

class CallExpressionSyntaxNodeEvaluatorTest extends TestCase
{
    /** @test */
    public function it_can_evaluate_a_call_expression_syntax_node_with_sqrt()
    {
        // Arrange
        $node = new CallExpressionSyntaxNode(
            new IdentifierSyntaxNode(Functions::SquareRoot->value),
            new LiteralSyntaxNode('2')
        );
        $evaluator = new CallExpressionSyntaxNodeEvaluator();
        $evaluator->setEvaluator(new Evaluator());

        // Act
        $result = $evaluator->evaluate($node);

        // Assert
        $this->assertEquals(sqrt(2), $result);
    }

    /** @test */
    public function it_can_evaluate_a_call_expression_syntax_node_with_sqrt_and_handle_exception()
    {
        // Arrange
        $node = new CallExpressionSyntaxNode(
            new IdentifierSyntaxNode(Functions::SquareRoot->value),
            new LiteralSyntaxNode('-1')
        );
        $evaluator = new CallExpressionSyntaxNodeEvaluator();
        $evaluator->setEvaluator(new Evaluator());

        // Assert
        $this->expectException(EvaluationException::class);
        $this->expectExceptionMessage("Cannot take the square root of a negative number.");

        // Act
        $evaluator->evaluate($node);
    }
}
