<?php

namespace CalcTek\Calculator\Lexer;

use Illuminate\Support\Collection;

class Lexer
{
    /**
     * @param string $input The input to lex
     *
     * @return Collection<Token> The tokens
     * @throws LexingException Thrown if the input is invalid
     */
    public function lex(string $input): Collection
    {
        $tokenizer = new Tokenizer($input);
        $tokenizer->tokenize();

        return $tokenizer->getTokens();
    }
}
