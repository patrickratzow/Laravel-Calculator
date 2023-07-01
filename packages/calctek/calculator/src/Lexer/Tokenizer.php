<?php

namespace CalcTek\Calculator\Lexer;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Tokenizer
{
    // Whitespace regex
    private const WHITESPACE_REGEX = '/\s+/';

    /**
     * @var int The current position the cursor is at
     */
    private int $position = 0;

    /**
     * @var string The input string
     */
    private string $input;

    /**
     * @var Collection<Token> The tokens that have been found
     */
    private Collection $tokens;

    /**
     * @var Token|null The current token
     */
    private ?Token $currentToken = null;

    public function __construct(string $input)
    {
        $this->input = $input;
        $this->tokens = new Collection();
    }

    /**
     * @return Collection<Token> The tokens that have been found
     */
    public function getTokens(): Collection
    {
        return $this->tokens;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function tokenize(): void
    {
        while ($this->moveNext()) {
            $token = $this->readToken();
            $this->tokens->push($token);
        }
    }

    private function moveNext(): bool
    {
        $this->trimSpaces();
        if ($this->position >= strlen($this->input)) {
            return false;
        }
        if ($this->scanIdentifier()) {
            return true;
        }
        if ($this->scanLiteral()) {
            return true;
        }
        if ($this->scanOperator()) {
            return true;
        }
        if ($this->scanSeparators()) {
            return true;
        }

        // We were unable to identity what we are dealing with, so we can't move further
        $this->resetCurrentToken();
        return false;
    }

    /**
     * @throws Exception If no token is found
     */
    private function readToken(): Token {
        if (empty($this->currentToken)) {
            throw new Exception("No token found, call moveNext() first");
        }

        return $this->currentToken;
    }

    /**
     * Trims all spaces until the next non-space character
     * @return void
     */
    private function trimSpaces(): void
    {
        $inputLength = strlen($this->input);

        while ($this->canAdvance()
            && Str::match(self::WHITESPACE_REGEX, $this->input[$this->position])) {
            // We found a whitespace, skip it
            $this->advance();
        }
    }

    private function resetCurrentToken(): void
    {
        $this->currentToken = null;
    }

    private const NUMBER_REGEX = '/[0-9]/';
    private const IDENTIFIER_REGEX = '/[a-zA-Z_0-9]/';

    /**
     * @return bool Whether a literal was found
     */
    private function scanIdentifier(): bool
    {
        $character = $this->input[$this->position];
        // Can't start with a number, but after that it's okay
        if (Str::match(self::NUMBER_REGEX, $character)) {
            return false;
        }
        // Must match the regex for identifiers
        if (!Str::match(self::IDENTIFIER_REGEX, $character)) {
            return false;
        }

        $buffer = $character;
        $this->advance();

        while ($this->canAdvance()) {
            $currentCharacter = $this->input[$this->position];
            if (!Str::match(self::IDENTIFIER_REGEX, $currentCharacter)) {
                break;
            }

            $buffer .= $currentCharacter;
            $this->advance();
        }

        $this->currentToken = new Token(TokenType::Identifier, $buffer);

        return true;
    }

    private function consume(string $text): bool
    {
        // If the text is longer than the remaining input, we can't consume it
        if (strlen($text) > (strlen($this->input) - $this->position)) {
            return false;
        }

        $textCutout = substr($this->input, $this->position, strlen($text));
        // If we found a match for the text, advance the cursor
        if ($textCutout === $text) {
            $this->advance(strlen($text));

            return true;
        }

        return false;
    }

    private function advance(int $length = 1): void
    {
        $this->position += $length;
    }

    private function canAdvance(int $length = 1): bool
    {
        // We have to -1 the length as we are already at a character, which itself is 1 in length.
        $currentPosition = $this->position + ($length - 1);
        $length = strlen($this->input);

        return $currentPosition < $length;
    }

    private function scanLiteral(): bool
    {
        $number = $this->parseNumber();
        if (empty($number)) {
            return false;
        }

        $this->currentToken = new Token(TokenType::Literal, $number);

        return true;
    }

    private function scanOperator(): bool
    {
        if ($this->consume('+')) {
            $this->currentToken = new Token(TokenType::Operator, '+');

            return true;
        }
        if ($this->consume('-')) {
            $this->currentToken = new Token(TokenType::Operator, '-');

            return true;
        }
        if ($this->consume('*')) {
            $this->currentToken = new Token(TokenType::Operator, '*');

            return true;
        }
        if ($this->consume('/')) {
            $this->currentToken = new Token(TokenType::Operator, '/');

            return true;
        }

        return false;
    }

    private function parseNumber(): string|null
    {
        $buffer = '';
        // If we see a '-' we need to check if it's a negative number or an operator
        if ($this->expect('-')) {
            // We want to trim spaces in case we are dealing with a number like "- 5"
            // This shouldn't really happen, but can't hurt to be paranoid ¯\_(ツ)_/¯
            $this->trimSpaces();
            // We need to ENSURE we are dealing with a number, so we go 1 character ahead
            $nextChar = $this->peek();
            // This could be an operator? It's not our responsibility to find out, return null
            if (empty($nextChar) || !Str::match(self::NUMBER_REGEX, $nextChar)) {
                return null;
            }

            $buffer .= '-';
            $this->advance();
        }

        $hasEncounteredDot = false;
        while ($this->canAdvance()) {
            $character = $this->input[$this->position];

            // Our calculator does not support more than 1 locale, so we can't have a comma
            if ($character == ".") {
                if ($hasEncounteredDot) {
                    return null;
                }

                $hasEncounteredDot = true;
                $buffer .= $character;
                $this->advance();

                continue;
            }

            if (Str::match(self::NUMBER_REGEX, $character)) {
                $buffer .= $character;
                $this->advance();

                continue;
            }
            // A whitespace is considered a complete break for the number
            if (Str::match(self::WHITESPACE_REGEX, $character)) {
                $this->advance();

                break;
            }



            break;
        }

        return $buffer;
    }

    private function scanSeparators(): bool
    {
        if ($this->consume('(')) {
            $this->currentToken = new Token(TokenType::Separator, '(');

            return true;
        }
        if ($this->consume(')')) {
            $this->currentToken = new Token(TokenType::Separator, ')');

            return true;
        }

        return false;
    }

    private function peek(int $length = 1): ?string
    {
        if (!$this->canAdvance($length)) {
            return null;
        }

        // We need to -1 the length as we are already at a character, which itself is 1 in length.
        return $this->input[$this->position + ($length - 1)];
    }

    /**
     * Checks if the next characters match the given text
     *
     * @param string $text
     * @return bool If the text was found
     */
    private function expect(string $text): bool
    {
        $length = strlen($text);

        return $this->peek($length) === $text;
    }
}
