<?php

namespace CalcTek\Calculator\Lexer;

use Illuminate\Support\Collection;

class Lexer
{
    /**
     * @param string $input The input to tokenize
     *
     * @return Collection<Token> The tokens
     * @throws \Exception Thrown if the input is invalid
     */
    public function tokenize(string $input): Collection
    {
        $tokenizer = new Tokenizer($input);
        $tokenizer->tokenize();

        return $tokenizer->getTokens();
    }
}
