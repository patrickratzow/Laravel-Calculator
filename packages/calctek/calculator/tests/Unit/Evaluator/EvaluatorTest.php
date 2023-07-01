<?php

namespace CalcTek\Calculator\Tests\Unit\Evaluator;

use CalcTek\Calculator\Evaluator\Evaluator;
use CalcTek\Calculator\Parser\Nodes\BinaryExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\CallExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\IdentifierSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\LiteralSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\UnaryExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Operator;
use Orchestra\Testbench\TestCase;

class EvaluatorTest extends TestCase
{
    /** @test */
    public function it_can_evaluate_a_literal_syntax_node()
    {
        // Arrange
        $node = new LiteralSyntaxNode('2');
        $evaluator = new Evaluator();

        // Act
        $result = $evaluator->evaluate($node);

        // Assert
        $this->assertEquals(2, $result);
    }

    /** @test */
    public function it_can_evaluate_a_binary_expression_syntax_node()
    {
        // Arrange
        $node = new BinaryExpressionSyntaxNode(
            Operator::Plus,
            new LiteralSyntaxNode('2'),
            new LiteralSyntaxNode('2')
        );
        $evaluator = new Evaluator();

        // Act
        $result = $evaluator->evaluate($node);

        // Assert
        $this->assertEquals(4, $result);
    }

    /** @test */
    public function it_can_evaluate_an_unary_expression_syntax_node()
    {
        // Arrange
        $node = new UnaryExpressionSyntaxNode(
            Operator::Minus,
            new LiteralSyntaxNode('2')
        );
        $evaluator = new Evaluator();

        // Act
        $result = $evaluator->evaluate($node);

        // Assert
        $this->assertEquals(-2, $result);
    }

    /** @test */
    public function it_can_evaluate_a_call_expression_syntax_node()
    {
        // Arrange
        $node = new CallExpressionSyntaxNode(
            new IdentifierSyntaxNode('sqrt'),
            new LiteralSyntaxNode('2'),
        );
        $evaluator = new Evaluator();

        // Act
        $result = $evaluator->evaluate($node);

        // Assert
        $this->assertEquals(sqrt(2), $result);
    }
}
