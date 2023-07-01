<?php


namespace CalcTek\Calculator\Tests\Unit\Parser;

use CalcTek\Calculator\Lexer\Token;
use CalcTek\Calculator\Lexer\TokenType;
use CalcTek\Calculator\Parser\Nodes\BinaryExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\CallExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\IdentifierSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\LiteralSyntaxNode;
use CalcTek\Calculator\Parser\Operator;
use CalcTek\Calculator\Parser\Parser;
use CalcTek\Calculator\Parser\SyntaxException;
use Orchestra\Testbench\TestCase;

/**
 * Generic tests that do not fit in any other file
 */
class ParserTest extends TestCase
{
    /** @test */
    public function it_can_parse_stretch_goal()
    {
        // Arrange
        // sqrt((((9*9)/12)+(13-4))*2)^2)
        $input = collect([
            new Token(TokenType::Identifier, 'sqrt'),
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Literal, '9'),
            new Token(TokenType::Operator, '*'),
            new Token(TokenType::Literal, '9'),
            new Token(TokenType::Separator, ')'),
            new Token(TokenType::Operator, '/'),
            new Token(TokenType::Literal, '12'),
            new Token(TokenType::Separator, ')'),
            new Token(TokenType::Operator, '+'),
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Literal, '13'),
            new Token(TokenType::Operator, '-'),
            new Token(TokenType::Literal, '4'),
            new Token(TokenType::Separator, ')'),
            new Token(TokenType::Separator, ')'),
            new Token(TokenType::Operator, '*'),
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Separator, ')'),
            new Token(TokenType::Operator, '^'),
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Separator, ')'),
        ]);
        $parser = new Parser($input);

        // Act
        $parser->parse();
        $ast = $parser->getAST();

        // Assert
        $this->assertEquals(
            new BinaryExpressionSyntaxNode(
                Operator::Power,
                new LiteralSyntaxNode('2'),
                new CallExpressionSyntaxNode(
                    new IdentifierSyntaxNode('sqrt'),
                    new BinaryExpressionSyntaxNode(
                        Operator::Multiply,
                        new LiteralSyntaxNode('2'),
                        new BinaryExpressionSyntaxNode(
                            Operator::Plus,
                            new BinaryExpressionSyntaxNode(
                                Operator::Minus,
                                new LiteralSyntaxNode('13'),
                                new LiteralSyntaxNode('4')
                            ),
                            new BinaryExpressionSyntaxNode(
                                Operator::Divide,
                                new LiteralSyntaxNode('12'),
                                new BinaryExpressionSyntaxNode(
                                    Operator::Multiply,
                                    new LiteralSyntaxNode('9'),
                                    new LiteralSyntaxNode('9')
                                ),
                            ),
                        )
                    )
                )
            ),
            $ast
        );
    }

    /** @test */
    public function it_can_ignore_extra_right_parentheses()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Operator, '+'),
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Separator, ')')
        ]);
        $parser = new Parser($input);

        // Act
        $parser->parse();
        $ast = $parser->getAST();

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
    public function it_can_ignore_redundant_parentheses()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Operator, '+'),
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Separator, ')')
        ]);
        $parser = new Parser($input);

        // Act
        $parser->parse();
        $ast = $parser->getAST();

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
    public function it_can_error_when_encountering_unexpected_parentheses()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Operator, '+'),
            new Token(TokenType::Literal, '2'),
        ]);
        $parser = new Parser($input);

        // Assert
        $this->expectException(SyntaxException::class);
        $this->expectExceptionMessage('Unexpected open parentheses immediately after a literal');

        // Act
        $parser->parse();
    }

    /** @test */
    public function it_can_error_when_encountering_unclosed_parentheses()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Literal, '2'),
            new Token(TokenType::Operator, '+'),
            new Token(TokenType::Literal, '2'),
        ]);
        $parser = new Parser($input);

        // Assert
        $this->expectException(SyntaxException::class);
        $this->expectExceptionMessage('Unclosed parentheses. Please close your parentheses');

        // Act
        $parser->parse();
    }
}
