<?php

namespace CalcTek\Calculator\Lexer;

class Token
{
    /**
     * @var TokenType The type of the token
     */
    private TokenType $type;
    /**
     * @var string The value of the token
     */
    private string $value;

    public function __construct(TokenType $type, string $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @return TokenType The type of the token
     */
    public function getType(): TokenType
    {
        return $this->type;
    }

    /**
     * @return string The value of the token
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
