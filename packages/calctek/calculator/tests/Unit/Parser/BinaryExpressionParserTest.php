<?php

namespace CalcTek\Calculator\Tests\Unit\Parser;

use CalcTek\Calculator\Lexer\Token;
use CalcTek\Calculator\Lexer\TokenType;
use CalcTek\Calculator\Parser\Nodes\BinaryExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\LiteralSyntaxNode;
use CalcTek\Calculator\Parser\Operator;
use CalcTek\Calculator\Parser\Parser;
use Orchestra\Testbench\TestCase;

class BinaryExpressionParserTest extends TestCase
{
    /** @test */
    public function it_can_parse_a_binary_expression()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Operator, '+'),
            new Token(TokenType::Literal, '2')
        ]);
        $parser = new Parser($input);

        // Act
        $ast = $parser->parse();

        // Assert
        $this->assertEquals(
            new BinaryExpressionSyntaxNode(
                Operator::Plus,
                new LiteralSyntaxNode('2'),
                new LiteralSyntaxNode('2')
            ),
            $ast
        );
    }

    /** @test */
    public function it_can_parse_a_binary_expression_with_precedence()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Operator, '+'),
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Operator, '*'),
            new Token(TokenType::Literal, '5')
        ]);
        $parser = new Parser($input);

        // Act
        $ast = $parser->parse();

        // Assert
        $this->assertEquals(
            new BinaryExpressionSyntaxNode(
                Operator::Plus,
                new LiteralSyntaxNode('2'),
                new BinaryExpressionSyntaxNode(
                    Operator::Multiply,
                    new LiteralSyntaxNode('2'),
                    new LiteralSyntaxNode('5')
                )
            ),
            $ast
        );
    }

    /** @test */
    public function it_can_parse_a_binary_expression_using_power_with_precedence()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Operator, '+'),
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Operator, '^'),
            new Token(TokenType::Literal, '5')
        ]);
        $parser = new Parser($input);

        // Act
        $ast = $parser->parse();

        // Assert
        $this->assertEquals(
            new BinaryExpressionSyntaxNode(
                Operator::Plus,
                new LiteralSyntaxNode('2'),
                new BinaryExpressionSyntaxNode(
                    Operator::Power,
                    new LiteralSyntaxNode('2'),
                    new LiteralSyntaxNode('5')
                )
            ),
            $ast
        );
    }

    /** @test */
    public function it_can_parse_a_binary_expression_with_correct_parentheses_precedence()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Operator, '+'),
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Separator, ')'),
            new Token(TokenType::Operator, '*'),
            new Token(TokenType::Literal, '5')
        ]);
        $parser = new Parser($input);

        // Act
        $ast = $parser->parse();

        // Assert
        $this->assertEquals(
            new BinaryExpressionSyntaxNode(
                Operator::Multiply,
                new BinaryExpressionSyntaxNode(
                    Operator::Plus,
                    new LiteralSyntaxNode('2'),
                    new LiteralSyntaxNode('2')
                ),
                new LiteralSyntaxNode('5'),
            ),
            $ast
        );
    }
}
