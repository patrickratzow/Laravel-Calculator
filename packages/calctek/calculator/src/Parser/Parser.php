<?php

namespace CalcTek\Calculator\Parser;

use CalcTek\Calculator\Lexer\Token;
use CalcTek\Calculator\Lexer\TokenType;
use CalcTek\Calculator\Parser\Nodes\BinaryExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\CallExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\IdentifierSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\LiteralSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\SyntaxNode;
use Illuminate\Support\Collection;

class Parser
{
    /**
     * @var Collection<Token> $tokens The tokens to parse
     */
    private Collection $tokens;

    /**
     * @var ?SyntaxNode $ast The parsed syntax nodes
     */
    private ?SyntaxNode $ast;

    /**
     * @var ?Token $previousToken The previous token
     */
    private ?Token $previousToken = null;

    /**
     * @var ?Token $token The current token
     */
    private ?Token $token = null;

    /**
     * @var ?Token $nextToken The next token
     */
    private ?Token $nextToken = null;

    /**
     * @var int $position The current position in the tokens array
     */
    private int $position = 0;

    /**
     * @param Collection<Token> $tokens The tokens to parse
     */
    public function __construct(Collection $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * @return SyntaxNode The parsed syntax nodes
     * @throws \Exception Thrown if there is no AST
     */
    public function getAST(): SyntaxNode
    {
        if (is_null($this->ast)) {
            throw new \Exception('No AST');
        }

        return $this->ast;
    }

    /**
     * @throws \Exception Thrown if the input is invalid
     */
    public function parse(): void
    {
        $this->token = $this->tokens[0];
        $this->nextToken = $this->peek();
        $ast = $this->parseTokens();
        $this->ast = $ast;

        // Current token should be null after we are done parsing
        if (!is_null($this->token)) {
            throw new \Exception('Expected end of input');
        }
    }


    /**
     * @throws \Exception
     */
    private function parseTokens(): SyntaxNode
    {
        $expression = null;
        // Identifiers are easy to parse, do them first
        if ($this->token?->getType() === TokenType::Identifier) {
            $expression = $this->parseIdentifier();

            // If the returned expression is an IdentifierSyntaxNode, we want to parse it as a call expression
            if (is_a($expression, IdentifierSyntaxNode::class)) {
                return $this->parseCallExpression($expression);
            }
        }
        $expression ??= $this->parseLiteral();

        return $expression;
    }

    private function parseCallExpression(IdentifierSyntaxNode $identifier): CallExpressionSyntaxNode
    {
        $arguments = new Collection();

        if (!$this->consume(new Token(TokenType::Separator, '('))) {
            throw new SyntaxException('Expected ( after function identifier');
        }

        while ($this->token?->getType() !== TokenType::Separator) {
            $arguments->push($this->parseTokens());
        }

        if (!$this->consume(new Token(TokenType::Separator, ')'))) {
            throw new SyntaxException('Expected ) after function arguments');
        }

        return new CallExpressionSyntaxNode($identifier, $arguments);
    }

    private function parsePrefixExpression(): ?SyntaxNode
    {
        if ($this->token?->getType() === TokenType::Identifier) {
            return $this->parseIdentifier();
        }

        return null;
    }

    /**
     * @throws SyntaxException
     */
    private function parseLiteral(int $minPrecedence = 0): ?SyntaxNode
    {
        $expression = null;
        if ($this->token?->getType() !== TokenType::Literal) {
            return $expression;
        }

        $expression = new LiteralSyntaxNode($this->token->getValue());
        if ($this->nextToken !== null && $this->nextToken->getType() === TokenType::Literal) {
            throw new SyntaxException('Invalid token, 2 literals in a row');
        }

        if ($this->nextToken !== null && $this->nextToken->getType() === TokenType::Operator) {
            $isNextTokenOperator = $this->isNextTokenOperator();
            if ($isNextTokenOperator) {
                $operator = $this->nextToken->getValue();
                // We skip the current literal & the operator here
                $this->next();
                $this->next();
                $nextExpression = $this->parseTokens();

                $expression = new BinaryExpressionSyntaxNode($operator, $expression, $nextExpression);
            }
        } else {
            $this->next();
        }

        return $expression;
    }

    private function isNextTokenOperator(): bool
    {
        return $this->nextToken?->getType() == TokenType::Operator;
    }

    private function parseIdentifier(): IdentifierSyntaxNode
    {
        $identifier = new IdentifierSyntaxNode($this->token->getValue());
        $this->next();

        return $identifier;
    }

    /**
     * @throws \Exception
     */
    private function parseToken(): SyntaxNode
    {
        $token = $this->token;
        $expression = $this->parseBinaryExpression($token);

        if (is_null($expression)) {
            // TODO: Bundle a lot more information for debugging purposes
            throw new \Exception('Invalid token');
        }

        return $expression;
    }

    private function parseBinaryExpression(Token $token): ?BinaryExpressionSyntaxNode
    {
        $type = $token->getType();
        if ($type === TokenType::Operator || $type === TokenType::Separator) {
            return null;
        }

        $left = $this->parsePrimaryExpression();
        if (is_null($left)) {
            return null;
        }

        $operator = $this->isNextTokenOperator();
        if (is_null($operator)) {
            return null;
        }

        $right = $this->parsePrimaryExpression();
        if (is_null($right)) {
            return null;
        }

        return new BinaryExpressionSyntaxNode($operator->getValue(), $left, $right);
    }

    private function parsePrimaryExpression(): ?SyntaxNode
    {
        return null;
    }

    private function peek(int $offset = 1): ?Token
    {
        $position = $this->position + $offset;
        if ($position >= $this->tokens->count()) {
            return null;
        }

        return $this->tokens[$position];
    }

    private function next(): void
    {
        $this->previousToken = $this->token;
        $this->token = $this->nextToken;
        $this->position++;
        $this->nextToken = $this->peek();
    }

    private function consume(Token $token): bool
    {
        if (is_null($this->token)) {
            return false;
        }

        if ($this->token->getType() !== $token->getType()
            || $this->token->getValue() !== $token->getValue()) {
            return false;
        }

        $this->next();

        return true;
    }

    private function dd(array $mixin = []): void
    {
        dd(array_merge($mixin, [
            'token' => $this->token,
            'nextToken' => $this->nextToken,
            'previousToken' => $this->previousToken,
            'position' => $this->position,
            'tokens' => $this->tokens,
        ]));
    }
}
