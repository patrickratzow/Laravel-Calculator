<?php

namespace CalcTek\Calculator\Tests\Feature\Evaluator\Evaluators;

use CalcTek\Calculator\Evaluator\EvaluationException;
use CalcTek\Calculator\Evaluator\Evaluator;
use CalcTek\Calculator\Evaluator\Evaluators\BinaryExpressionSyntaxNodeEvaluator;
use CalcTek\Calculator\Parser\Nodes\BinaryExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\LiteralSyntaxNode;
use CalcTek\Calculator\Parser\Operator;
use Orchestra\Testbench\TestCase;

class BinaryExpressionSyntaxNodeEvaluatorTest extends TestCase
{
    /** @test */
    public function it_can_evaluate_a_binary_expression_syntax_node_with_plus_operator()
    {
        // Arrange
        $node = new BinaryExpressionSyntaxNode(
            Operator::Plus,
            new LiteralSyntaxNode('2'),
            new LiteralSyntaxNode('7')
        );
        $evaluator = new BinaryExpressionSyntaxNodeEvaluator();
        $evaluator->setEvaluator(new Evaluator());

        // Act
        $result = $evaluator->evaluate($node);

        // Assert
        $this->assertEquals(9, $result);
    }

    /** @test */
    public function it_can_evaluate_a_binary_expression_syntax_node_with_minus_operator()
    {
        // Arrange
        $node = new BinaryExpressionSyntaxNode(
            Operator::Minus,
            new LiteralSyntaxNode('2'),
            new LiteralSyntaxNode('1')
        );
        $evaluator = new BinaryExpressionSyntaxNodeEvaluator();
        $evaluator->setEvaluator(new Evaluator());

        // Act
        $result = $evaluator->evaluate($node);

        // Assert
        $this->assertEquals(1, $result);
    }

    /** @test */
    public function it_can_evaluate_a_binary_expression_syntax_node_with_minus_operator_that_results_in_negative_number()
    {
        // Arrange
        $node = new BinaryExpressionSyntaxNode(
            Operator::Minus,
            new LiteralSyntaxNode('2'),
            new LiteralSyntaxNode('7')
        );
        $evaluator = new BinaryExpressionSyntaxNodeEvaluator();
        $evaluator->setEvaluator(new Evaluator());

        // Act
        $result = $evaluator->evaluate($node);

        // Assert
        $this->assertEquals(-5, $result);
    }

    /** @test */
    public function it_can_evaluate_a_binary_expression_syntax_node_with_multiply_operator()
    {
        // Arrange
        $node = new BinaryExpressionSyntaxNode(
            Operator::Multiply,
            new LiteralSyntaxNode('2'),
            new LiteralSyntaxNode('7')
        );
        $evaluator = new BinaryExpressionSyntaxNodeEvaluator();
        $evaluator->setEvaluator(new Evaluator());

        // Act
        $result = $evaluator->evaluate($node);

        // Assert
        $this->assertEquals(14, $result);
    }

    /** @test */
    public function it_can_evaluate_a_binary_expression_syntax_node_with_divide_operator()
    {
        // Arrange
        $node = new BinaryExpressionSyntaxNode(
            Operator::Divide,
            new LiteralSyntaxNode('6'),
            new LiteralSyntaxNode('2'),
        );
        $evaluator = new BinaryExpressionSyntaxNodeEvaluator();
        $evaluator->setEvaluator(new Evaluator());

        // Act
        $result = $evaluator->evaluate($node);

        // Assert
        $this->assertEquals(3, $result);
    }

    /** @test */
    public function it_can_evaluate_a_binary_expression_syntax_node_with_power_operator()
    {
        // Arrange
        $node = new BinaryExpressionSyntaxNode(
            Operator::Power,
            new LiteralSyntaxNode('5'),
            new LiteralSyntaxNode('2'),
        );
        $evaluator = new BinaryExpressionSyntaxNodeEvaluator();
        $evaluator->setEvaluator(new Evaluator());

        // Act
        $result = $evaluator->evaluate($node);

        // Assert
        $this->assertEquals(25, $result);
    }

    /** @test */
    public function it_can_evaluate_a_binary_expression_syntax_node_that_divides_by_zero()
    {
        // Arrange
        $node = new BinaryExpressionSyntaxNode(
            Operator::Divide,
            new LiteralSyntaxNode('25'),
            new LiteralSyntaxNode('0'),
        );
        $evaluator = new BinaryExpressionSyntaxNodeEvaluator();
        $evaluator->setEvaluator(new Evaluator());

        // Assert
        $this->expectException(EvaluationException::class);
        $this->expectExceptionMessage('Cannot divide by zero.');

        // Act
        $evaluator->evaluate($node);
    }
}
