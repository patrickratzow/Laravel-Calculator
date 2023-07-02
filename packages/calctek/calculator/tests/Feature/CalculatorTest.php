<?php

namespace CalcTek\Calculator\Tests\Feature;

use CalcTek\Calculator\Evaluator\Evaluator;
use CalcTek\Calculator\Lexer\Lexer;
use CalcTek\Calculator\Parser\Parser;
use Orchestra\Testbench\TestCase;

class CalculatorTest extends TestCase
{
    /** @test */
    public function it_can_calculate_stretch_goal()
    {
        // Arrange
        $input = 'sqrt((((9*9)/12)+(13-4))*2)^2)';
        $lexer = new Lexer();
        $parser = new Parser($lexer->lex($input));
        $ast = $parser->parse();
        $evaluator = new Evaluator();

        // Act
        $result = $evaluator->evaluate($ast);

        // Assert
        $this->assertEquals(31.5, $result);
    }

    /**
     * @test
     * @dataProvider randomExpressions
     */
    public function it_can_calculate_input($input, $expected)
    {
        // Arrange
        $lexer = new Lexer();
        $parser = new Parser($lexer->lex($input));
        $ast = $parser->parse();
        $evaluator = new Evaluator();

        // Act
        $result = $evaluator->evaluate($ast);

        // Assert
        $this->assertEqualsWithDelta($expected, $result, 0.0001);
    }

    /*
     * These are just random generated expressions that I used to test the calculator. Generated with ChatGPT May 24
     *
     * Prompt:
     * Generate 25 math expressions in an array with the corresponding result.
     * Use sqrt, pi and log in some of the expressions. Please notice that log is actually log10
     *
     * It should look like this so it looks like this
     * [
     *   [mathExpression, result],
     *   [mathExpression, result],
     *   ...
     * ]
     */
    public static function randomExpressions(): array
    {
        return [
            ["2 + 4", 6],
            ["5 * 7", 35],
            ["10 - 3", 7],
            ["8 / 2", 4],
            ["sqrt(9)", 3],
            ["pi * 5", 15.707963267948966],
            ["log(10)", 1.0],
            ["6 + 9 - 2", 13],
            ["3 * (7 - 2)", 15],
            ["12 / (4 + 2)", 2],
            ["9 - (5 * 2)", -1],
            ["4 + 6 / 2", 7],
            ["sqrt(16)", 4],
            ["8 * (3 - 1) / 4", 4],
            ["2 + 3", 5],
            ["6 * 4", 24],
            ["20 - 8", 12],
            ["15 / 3", 5],
            ["sqrt(25)", 5],
            ["2 + sqrt(pi)", 3.77245385091],
            ["log(100)", 2.0],
            ["pi * 3^2", 28.274333882308138],
            ["log(2^8)", 2.40823996531],
            ["sqrt(9) + sqrt(16)", 7],
            ["sqrt(pi) * 4", 7.08981540362],
            ["log(1000) / log(10)", 3.0]
        ];
    }
}
