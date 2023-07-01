<?php


namespace CalcTek\Calculator\Tests\Unit\Parser;

use CalcTek\Calculator\Lexer\Token;
use CalcTek\Calculator\Lexer\TokenType;
use CalcTek\Calculator\Parser\Nodes\BinaryExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\CallExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\IdentifierSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\LiteralSyntaxNode;
use CalcTek\Calculator\Parser\Parser;
use Orchestra\Testbench\TestCase;

class ParserTest extends TestCase
{
    /** @test */
    public function it_can_parse_a_function_call()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Identifier, 'sqrt'),
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Separator, ')'),
        ]);
        $parser = new Parser($input);

        // Act
        $parser->parse();
        $ast = $parser->getAST();

        // Assert
        $this->assertEquals(
            new CallExpressionSyntaxNode(
                new IdentifierSyntaxNode('sqrt'),
                collect([
                    new LiteralSyntaxNode('2')
                ])
            ),
            $ast
        );
    }

    /** @test */
    public function it_can_parse_a_nested_function_call()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Identifier, 'sqrt'),
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Identifier, 'sqrt'),
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Separator, ')'),
            new Token(TokenType::Separator, ')'),
        ]);
        $parser = new Parser($input);

        // Act
        $parser->parse();
        $ast = $parser->getAST();

        // Assert
        $this->assertEquals(
            new CallExpressionSyntaxNode(
                new IdentifierSyntaxNode('sqrt'),
                collect([
                    new CallExpressionSyntaxNode(
                        new IdentifierSyntaxNode('sqrt'),
                        collect([
                            new LiteralSyntaxNode('2')
                        ])
                    ),
                ])
            ),
            $ast
        );
    }

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
        $parser->parse();
        $ast = $parser->getAST();

        // Assert
        $this->assertEquals(
            new BinaryExpressionSyntaxNode(
                '+',
                new LiteralSyntaxNode('2'),
                new LiteralSyntaxNode('2')
            ),
            $ast
        );
    }

    /** @test */
    public function it_can_parse_a_function_call_and_a_binary_expression()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Identifier, 'sqrt'),
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Operator, '+'),
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Separator, ')'),
        ]);
        $parser = new Parser($input);

        // Act
        $parser->parse();
        $ast = $parser->getAST();

        // Assert
        $this->assertEquals(
            new CallExpressionSyntaxNode(
                new IdentifierSyntaxNode('sqrt'),
                collect([
                    new BinaryExpressionSyntaxNode(
                        '+',
                        new LiteralSyntaxNode('2'),
                        new LiteralSyntaxNode('2')
                    ),
                ])
            ),
            $ast
        );
    }
}
