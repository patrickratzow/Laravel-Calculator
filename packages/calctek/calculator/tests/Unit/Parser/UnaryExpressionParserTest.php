<?php

namespace CalcTek\Calculator\Tests\Unit\Parser;

use CalcTek\Calculator\Lexer\Token;
use CalcTek\Calculator\Lexer\TokenType;
use CalcTek\Calculator\Parser\Functions;
use CalcTek\Calculator\Parser\Nodes\BinaryExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\CallExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\IdentifierSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\LiteralSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\UnaryExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Operator;
use CalcTek\Calculator\Parser\Parser;
use CalcTek\Calculator\Parser\SyntaxException;
use Orchestra\Testbench\TestCase;

class UnaryExpressionParserTest extends TestCase
{
    /** @test */
    public function it_can_parse_an_unary_expression()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Operator, '-'),
            new Token(TokenType::Literal, '2'),
        ]);
        $parser = new Parser($input);

        // Act
        $ast = $parser->parse();

        // Assert
        $this->assertEquals(
            new UnaryExpressionSyntaxNode(
                Operator::Minus,
                new LiteralSyntaxNode('2')
            ),
            $ast
        );
    }

    /** @test */
    public function it_can_parse_an_unary_expression_in_a_binary_expression()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Operator, '+'),
            new Token(TokenType::Operator, '-'),
            new Token(TokenType::Literal, '2'),
        ]);
        $parser = new Parser($input);

        // Act
        $ast = $parser->parse();

        // Assert
        $this->assertEquals(
            new BinaryExpressionSyntaxNode(
                Operator::Plus,
                new LiteralSyntaxNode('2'),
                new UnaryExpressionSyntaxNode(
                    Operator::Minus,
                    new LiteralSyntaxNode('2')
                )
            ),
            $ast
        );
    }

    /** @test */
    public function it_can_parse_an_unary_expression_with_a_function_call_expression()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Operator, '-'),
            new Token(TokenType::Identifier, Functions::SquareRoot->value),
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Separator, ')'),
        ]);
        $parser = new Parser($input);

        // Act
        $ast = $parser->parse();

        // Assert
        $this->assertEquals(
            new UnaryExpressionSyntaxNode(
                Operator::Minus,
                new CallExpressionSyntaxNode(
                    new IdentifierSyntaxNode(Functions::SquareRoot->value),
                    new LiteralSyntaxNode('2')
                ),
            ),
            $ast
        );
    }

    /** @test */
    public function it_can_error_when_unexpected_unary_operator_is_attempted()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Operator, '+'),
            new Token(TokenType::Literal, '2'),
        ]);
        $parser = new Parser($input);

        // Assert
        $this->expectException(SyntaxException::class);
        $this->expectExceptionMessage('Unexpected operator before an unary expression');

        // Act
        $parser->parse();
    }
}
