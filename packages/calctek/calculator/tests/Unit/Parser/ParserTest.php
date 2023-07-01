<?php


namespace CalcTek\Calculator\Tests\Unit\Parser;

use CalcTek\Calculator\Lexer\Token;
use CalcTek\Calculator\Lexer\TokenType;
use CalcTek\Calculator\Parser\Parser;
use Orchestra\Testbench\TestCase;

class ParserTest extends TestCase
{
    /** @test */
    public function it_can_parse_a_binary_expression()
    {
        // Arrange
        $input = collect([
            new Token(TokenType::Literal, 1),
            new Token(TokenType::Operator, '+'),
            new Token(TokenType::Literal, 2)
        ]);
        $parser = new Parser($input);

        // Act
        $parser->parse();
        $ast = $parser->getAST();

        // Assert
        dd($ast);
    }
}
