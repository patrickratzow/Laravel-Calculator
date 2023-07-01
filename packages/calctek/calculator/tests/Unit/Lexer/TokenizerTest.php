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
        $tokens = $tokenizer->getTokens()->toArray();

        // Assert
        $this->assertEquals($tokens, [
            new Token(TokenType::Literal, 1)
        ]);
    }


    /** @test */
    public function it_can_tokenize_an_operator()
    {
        // Arrange
        $input = '+';
        $tokenizer = new Tokenizer($input);

        // Act
        $tokenizer->tokenize();
        $tokens = $tokenizer->getTokens()->toArray();

        // Assert
        $this->assertEquals($tokens, [
            new Token(TokenType::Operator, '+')
        ]);
    }

    /** @test */
    public function it_can_tokenize_an_identifier()
    {
        // Arrange
        $input = 'sqrt';
        $tokenizer = new Tokenizer($input);

        // Act
        $tokenizer->tokenize();
        $tokens = $tokenizer->getTokens()->toArray();

        // Assert
        $this->assertEquals($tokens, [
            new Token(TokenType::Identifier, 'sqrt')
        ]);
    }

    /** @test */
    public function it_can_tokenize_a_separator()
    {
        // Arrange
        $input = '(';
        $tokenizer = new Tokenizer($input);

        // Act
        $tokenizer->tokenize();
        $tokens = $tokenizer->getTokens()->toArray();

        // Assert
        $this->assertEquals($tokens, [
            new Token(TokenType::Separator, '(')
        ]);
    }

    /** @test */
    public function it_can_tokenize_a_function_call()
    {
        // Arrange
        $input = 'sqrt(5)';
        $tokenizer = new Tokenizer($input);

        // Act
        $tokenizer->tokenize();
        $tokens = $tokenizer->getTokens()->toArray();

        // Assert
        $this->assertEquals($tokens, [
            new Token(TokenType::Identifier, 'sqrt'),
            new Token(TokenType::Separator, '('),
            new Token(TokenType::Literal, 5),
            new Token(TokenType::Separator, ')')
        ]);
    }

    /** @test */
    public function it_can_tokenize_addition()
    {
        // Arrange
        $input = '2+5';
        $tokenizer = new Tokenizer($input);

        // Act
        $tokenizer->tokenize();
        $tokens = $tokenizer->getTokens()->toArray();

        // Assert
        $this->assertEquals($tokens, [
            new Token(TokenType::Literal, 2),
            new Token(TokenType::Operator, '+'),
            new Token(TokenType::Literal, 5)
        ]);
    }

    /** @test */
    public function it_can_tokenize_subtraction()
    {
        // Arrange
        $input = '2-5';
        $tokenizer = new Tokenizer($input);

        // Act
        $tokenizer->tokenize();
        $tokens = $tokenizer->getTokens()->toArray();

        // Assert
        $this->assertEquals($tokens, [
            new Token(TokenType::Literal, 2),
            new Token(TokenType::Operator, '-'),
            new Token(TokenType::Literal, 5)
        ]);
    }

    /** @test */
    public function it_can_tokenize_multiplication()
    {
        // Arrange
        $input = '2*5';
        $tokenizer = new Tokenizer($input);

        // Act
        $tokenizer->tokenize();
        $tokens = $tokenizer->getTokens()->toArray();

        // Assert
        $this->assertEquals($tokens, [
            new Token(TokenType::Literal, 2),
            new Token(TokenType::Operator, '*'),
            new Token(TokenType::Literal, 5)
        ]);
    }

    /** @test */
    public function it_can_tokenize_division()
    {
        // Arrange
        $input = '2/5';
        $tokenizer = new Tokenizer($input);

        // Act
        $tokenizer->tokenize();
        $tokens = $tokenizer->getTokens()->toArray();

        // Assert
        $this->assertEquals($tokens, [
            new Token(TokenType::Literal, 2),
            new Token(TokenType::Operator, '/'),
            new Token(TokenType::Literal, 5)
        ]);
    }
}
