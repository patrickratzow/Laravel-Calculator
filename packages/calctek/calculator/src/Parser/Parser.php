<?php

namespace CalcTek\Calculator\Parser;

use CalcTek\Calculator\Lexer\Token;
use CalcTek\Calculator\Parser\Nodes\SyntaxNode;
use Illuminate\Support\Collection;

class Parser
{
    /**
     * @var Collection<Token> $tokens The tokens to parse
     */
    private Collection $tokens;

    /**
     * @var Collection<SyntaxNode> $syntaxNodes The parsed syntax nodes
     */
    private Collection $syntaxNodes;

    /**
     * @var ?Token $previousToken The previous token
     */
    private ?Token $previousToken = null;

    /**
     * @var ?Token $nextToken The next token
     */
    private ?Token $nextToken = null;

    /**
     * @var ?SyntaxNode $previousNode The previous syntax node
     */
    private ?SyntaxNode $previousNode = null;


    /**
     * @param Collection<Token> $tokens The tokens to parse
     */
    public function __construct(Collection $tokens)
    {
        $this->tokens = $tokens;
        $this->syntaxNodes = new Collection();
    }

    /**
     * @throws \Exception Thrown if the input is invalid
     */
    public function parse(): void
    {
        $tokensCount = $this->tokens->count();
        for ($i = 0; $i < $tokensCount; $i++) {
            // Set the previous token if we're not at the first token
            if ($i > 0) {
                $this->previousToken = $this->tokens[$i - 1];
            }
            // Set the next token if we're not at the last token
            if ($i < $tokensCount - 1) {
                $this->nextToken = $this->tokens[$i + 1];
            } else {
                $this->nextToken = null;
            }

            $token = $this->tokens[$i];
            $node = $this->parseToken($token);

            // TODO: Is this the best way to handle this? It seems like it could be improved
            // If the node is null, it means it was a token that was skipped
            if (is_null($node)) {
                continue;
            }

            $this->syntaxNodes->push($node);
            $this->previousNode = $node;
        }
    }

    /**
     * @return Collection<SyntaxNode> The parsed syntax nodes
     */
    public function getAST(): Collection
    {
        return $this->syntaxNodes;
    }
}
