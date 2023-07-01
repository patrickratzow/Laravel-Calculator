<?php

namespace CalcTek\Calculator\Lexer;

use Illuminate\Support\Collection;

class Lexer
{
    /**
     * @param string input
     *
     * @return Collection<Token>
     */
    public function tokenize(string $input): Collection
    {
        $tokenizer = new Tokenizer($input);
        $tokenizer->tokenize();
        $tokens = $tokenizer->getTokens();

        return $tokenizer->getTokens();
    }
}
