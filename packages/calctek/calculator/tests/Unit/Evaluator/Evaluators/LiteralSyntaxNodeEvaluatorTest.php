<?php

namespace CalcTek\Calculator\Tests\Unit\Evaluator\Evaluators;

use CalcTek\Calculator\Evaluator\Evaluators\LiteralSyntaxNodeEvaluator;
use CalcTek\Calculator\Parser\Nodes\LiteralSyntaxNode;
use Orchestra\Testbench\TestCase;

class LiteralSyntaxNodeEvaluatorTest extends TestCase
{
    /** @test */
    public function it_can_evaluate_a_literal_syntax_node()
    {
        // Arrange
        $node = new LiteralSyntaxNode('2');
        $evaluator = new LiteralSyntaxNodeEvaluator();

        // Act
        $result = $evaluator->evaluate($node);

        // Assert
        $this->assertEquals(2, $result);
    }

    /** @test */
    public function it_can_evaluate_a_literal_syntax_node_with_decimal_number()
    {
        // Arrange
        $node = new LiteralSyntaxNode('2.5');
        $evaluator = new LiteralSyntaxNodeEvaluator();

        // Act
        $result = $evaluator->evaluate($node);

        // Assert
        $this->assertEquals(2.5, $result);
    }
}
