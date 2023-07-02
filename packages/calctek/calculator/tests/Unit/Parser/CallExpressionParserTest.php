<?php

namespace CalcTek\Calculator\Tests\Unit\Parser;

use CalcTek\Calculator\Lexer\Token;
use CalcTek\Calculator\Lexer\TokenType;
use CalcTek\Calculator\Parser\Functions;
use CalcTek\Calculator\Parser\Nodes\BinaryExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\CallExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\IdentifierSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\LiteralSyntaxNode;
use CalcTek\Calculator\Parser\Operator;
use CalcTek\Calculator\Parser\Parser;
use Orchestra\Testbench\TestCase;

class CallExpressionParserTest extends TestCase
{
    /** @test */
    public function it_can_parse_a_call()
    {
        // Arrange
        $input = collect([
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
            new CallExpressionSyntaxNode(
                new IdentifierSyntaxNode(Functions::SquareRoot->value),
                new LiteralSyntaxNode('2')
            ),
            $ast
        );
    }

    /** @test */
    public function it_can_parse_a_nested_call()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Identifier, Functions::SquareRoot->value),
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Identifier, Functions::SquareRoot->value),
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Separator, ')'),
            new Token(TokenType::Separator, ')'),
        ]);
        $parser = new Parser($input);

        // Act
        $ast = $parser->parse();

        // Assert
        $this->assertEquals(
            new CallExpressionSyntaxNode(
                new IdentifierSyntaxNode(Functions::SquareRoot->value),
                new CallExpressionSyntaxNode(
                    new IdentifierSyntaxNode(Functions::SquareRoot->value),
                    new LiteralSyntaxNode('2')
                )
            ),
            $ast
        );
    }

    /** @test */
    public function it_can_parse_a_call_and_a_binary_expression()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Identifier, Functions::SquareRoot->value),
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Operator, '+'),
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Separator, ')'),
        ]);
        $parser = new Parser($input);

        // Act
        $ast = $parser->parse();

        // Assert
        $this->assertEquals(
            new CallExpressionSyntaxNode(
                new IdentifierSyntaxNode(Functions::SquareRoot->value),
                new BinaryExpressionSyntaxNode(
                    Operator::Plus,
                    new LiteralSyntaxNode('2'),
                    new LiteralSyntaxNode('2')
                ),
            ),
            $ast
        );
    }
}
