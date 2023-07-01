<?php

namespace CalcTek\Calculator\Tests\Unit\Lexer;

use CalcTek\Calculator\Lexer\Token;
use CalcTek\Calculator\Lexer\Tokenizer;
use CalcTek\Calculator\Lexer\TokenType;
use Orchestra\Testbench\TestCase;

class TokenizerTest extends TestCase
{
    /** @test */
    public function it_can_tokenize_a_literal()
    {
        // Arrange
        $input = '1';
        $tokenizer = new Tokenizer($input);

        // Act
        $tokenizer->tokenize();
        $tokens = $tokenizer->getTokens();

        // Assert
        $this->assertEquals(collect([
            new Token(TokenType::Literal, 1)
        ]), $tokens);
    }


    /** @test */
    public function it_can_tokenize_an_operator()
    {
        // Arrange
        $input = '+';
        $tokenizer = new Tokenizer($input);

        // Act
        $tokenizer->tokenize();
        $tokens = $tokenizer->getTokens();

        // Assert
        $this->assertEquals(collect([
            new Token(TokenType::Operator, '+')
        ]), $tokens);
    }

    /** @test */
    public function it_can_tokenize_an_identifier()
    {
        // Arrange
        $input = 'sqrt';
        $tokenizer = new Tokenizer($input);

        // Act
        $tokenizer->tokenize();
        $tokens = $tokenizer->getTokens();

        // Assert
        $this->assertEquals(collect([
            new Token(TokenType::Identifier, 'sqrt')
        ]), $tokens);
    }

    /** @test */
    public function it_can_tokenize_a_separator()
    {
        // Arrange
        $input = '(';
        $tokenizer = new Tokenizer($input);

        // Act
        $tokenizer->tokenize();
        $tokens = $tokenizer->getTokens();

        // Assert
        $this->assertEquals(collect([
            new Token(TokenType::Separator, '(')
        ]), $tokens);
    }

    /** @test */
    public function it_can_tokenize_a_function_call()
    {
        // Arrange
        $input = 'sqrt(5)';
        $tokenizer = new Tokenizer($input);

        // Act
        $tokenizer->tokenize();
        $tokens = $tokenizer->getTokens();

        // Assert
        $this->assertEquals(collect([
            new Token(TokenType::Identifier, 'sqrt'),
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Literal, 5),
            new Token(TokenType::Separator, ')')
        ]), $tokens);
    }

    /** @test */
    public function it_can_tokenize_addition()
    {
        // Arrange
        $input = '2+5';
        $tokenizer = new Tokenizer($input);

        // Act
        $tokenizer->tokenize();
        $tokens = $tokenizer->getTokens();

        // Assert
        $this->assertEquals(collect([
            new Token(TokenType::Literal, 2),
            new Token(TokenType::Operator, '+'),
            new Token(TokenType::Literal, 5)
        ]), $tokens);
    }

    /** @test */
    public function it_can_tokenize_subtraction()
    {
        // Arrange
        $input = '2-5';
        $tokenizer = new Tokenizer($input);

        // Act
        $tokenizer->tokenize();
        $tokens = $tokenizer->getTokens();

        // Assert
        $this->assertEquals(collect([
            new Token(TokenType::Literal, 2),
            new Token(TokenType::Operator, '-'),
            new Token(TokenType::Literal, 5)
        ]), $tokens);
    }

    /** @test */
    public function it_can_tokenize_multiplication()
    {
        // Arrange
        $input = '2*5';
        $tokenizer = new Tokenizer($input);

        // Act
        $tokenizer->tokenize();
        $tokens = $tokenizer->getTokens();

        // Assert
        $this->assertEquals(collect([
            new Token(TokenType::Literal, 2),
            new Token(TokenType::Operator, '*'),
            new Token(TokenType::Literal, 5)
        ]), $tokens);
    }

    /** @test */
    public function it_can_tokenize_division()
    {
        // Arrange
        $input = '2/5';
        $tokenizer = new Tokenizer($input);

        // Act
        $tokenizer->tokenize();
        $tokens = $tokenizer->getTokens();

        // Assert
        $this->assertEquals(collect([
            new Token(TokenType::Literal, 2),
            new Token(TokenType::Operator, '/'),
            new Token(TokenType::Literal, 5)
        ]), $tokens);
    }

    /** @test */
    public function it_can_tokenize_power()
    {
        // Arrange
        $input = '2^5';
        $tokenizer = new Tokenizer($input);

        // Act
        $tokenizer->tokenize();
        $tokens = $tokenizer->getTokens();

        // Assert
        $this->assertEquals(collect([
            new Token(TokenType::Literal, 2),
            new Token(TokenType::Operator, '^'),
            new Token(TokenType::Literal, 5)
        ]), $tokens);
    }

    /**
     * Any consumer of this package should check for empty input themselves
     * However we want to ensure we don't crash their application despite their stupidity
     *
     * @test
     */
    public function it_wont_error_on_empty_input()
    {
        // Arrange
        $input = '';
        $tokenizer = new Tokenizer($input);

        // Act
        $tokenizer->tokenize();
        $tokens = $tokenizer->getTokens();

        // Assert
        $this->assertEmpty($tokens);
    }
}
