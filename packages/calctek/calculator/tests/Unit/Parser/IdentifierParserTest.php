<?php

namespace CalcTek\Calculator\Tests\Unit\Parser;

use CalcTek\Calculator\Lexer\Token;
use CalcTek\Calculator\Lexer\TokenType;
use CalcTek\Calculator\Parser\Functions;
use CalcTek\Calculator\Parser\Parser;
use CalcTek\Calculator\Parser\SyntaxException;
use Orchestra\Testbench\TestCase;

class IdentifierParserTest extends TestCase
{
    /**
     * 'sqrt' is invalid syntax, an identifier must be followed by a '('
     *
     * @test
     */
    public function it_can_throw_syntax_exception_when_identifier_is_an_unknown_function()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Identifier, "sqrt2"),
        ]);
        $parser = new Parser($input);

        // Assert
        $this->expectException(SyntaxException::class);
        $this->expectExceptionMessage('sqrt2 is not a valid function');

        // Act
        $parser->parse();
    }

    /**
     * 'sqrt' is invalid syntax, an identifier must be followed by a '('
     *
     * @test
     */
    public function it_can_throw_syntax_exception_when_identifier_is_not_followed_by_a_left_parenthesis()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Identifier, Functions::SquareRoot->value),
        ]);
        $parser = new Parser($input);

        // Assert
        $this->expectException(SyntaxException::class);
        $this->expectExceptionMessage('Expected ( after function identifier');

        // Act
        $parser->parse();
    }

    /**
     * 'sqrt(5' is invalid syntax, an identifier must be closed by a ')'
     *
     * @test
     */
    public function it_can_throw_syntax_exception_when_identifier_is_not_closed_by_a_right_parenthesis()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Identifier, Functions::SquareRoot->value),
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Literal, '5')
        ]);
        $parser = new Parser($input);

        // Assert
        $this->expectException(SyntaxException::class);
        $this->expectExceptionMessage('Expected ) after function arguments');

        // Act
        $parser->parse();
    }

    /**
     * 'sqrt()' is invalid syntax, an identifier must have at least 1 argument
     *
     * @test
     */
    public function it_can_throw_syntax_exception_when_identifier_has_no_arguments()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Identifier, Functions::SquareRoot->value),
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Separator, ')'),
        ]);
        $parser = new Parser($input);

        // Assert
        $this->expectException(SyntaxException::class);
        $this->expectExceptionMessage('Expected at least 1 argument for a function call');

        // Act
        $parser->parse();
    }
}
