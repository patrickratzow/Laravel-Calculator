<?php

namespace CalcTek\Calculator\Parser;

use CalcTek\Calculator\Lexer\Token;
use CalcTek\Calculator\Lexer\TokenType;
use CalcTek\Calculator\Parser\Nodes\BinaryExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\CallExpressionSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\IdentifierSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\LiteralSyntaxNode;
use CalcTek\Calculator\Parser\Nodes\SyntaxNode;
use CalcTek\Calculator\Parser\Nodes\UnaryExpressionSyntaxNode;
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
     * @var int $parenthesesDepth The current depth of parentheses
     */
    private int $parenthesesDepth = 0;

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
        $ast = $this->parseExpression();
        $this->skipRedundantParentheses();
        $this->ast = $ast;

        //$this->dd();

        // Current token should be null after we are done parsing
        if (!is_null($this->token)) {
            throw new \Exception('Expected end of input');
        }
    }

    /**
     * @throws SyntaxException
     */
    private function parseExpression(): ?SyntaxNode
    {
        $expression = null;
        // TODO: Deal with parentheses and order of operations
        if ($this->token?->getType() === TokenType::Operator && $this->token?->getValue() === '-') {
            $expression = $this->parseUnaryExpression();
        }
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

    /**
     * @throws SyntaxException
     */
    private function parseUnaryExpression(): ?UnaryExpressionSyntaxNode
    {
        if (!$this->consume(new Token(TokenType::Operator, '-'))) {
            return null;
        }

        $operand = $this->parseExpression();
        if (is_null($operand)) {
            throw new SyntaxException('Expected expression after unary');
        }

        return new UnaryExpressionSyntaxNode(Operator::Minus, $operand);
    }

    /**
     * @throws SyntaxException
     */
    private function parseCallExpression(IdentifierSyntaxNode $identifier): CallExpressionSyntaxNode
    {
        /* @var Collection<SyntaxNode> $arguments */
        $arguments = new Collection();

        if (!$this->consume(new Token(TokenType::Separator, '('))) {
            throw new SyntaxException('Expected ( after function identifier');
        }

        while ($this->token?->getType() !== TokenType::Separator) {
            $expression = $this->parseExpression();

            // Deal with cases where can't seem to parse it to an expression
            if (is_null($expression)) {
                // We are at the end of the input, we are missing a )
                if (is_null($this->nextToken)) {
                    throw new SyntaxException('Expected ) after function arguments');
                }
                // We can't parse an expression, so we can't parse the arguments

                throw new SyntaxException('Unable to parse function arguments');
            }

            $arguments->push($expression);
        }

        if (!$this->consume(new Token(TokenType::Separator, ')'))) {
            throw new SyntaxException('Expected ) after function arguments');
        }

        if ($arguments->isEmpty()) {
            throw new SyntaxException('Expected at least 1 argument for a function call');
        }

        return new CallExpressionSyntaxNode($identifier, $arguments);
    }

    /**
     * Skips the remaining tokens if they are all right parentheses
     *
     * @return void
     * @throws SyntaxException Thrown if there is a mismatched parentheses
     */
    public function skipRedundantParentheses(): void
    {
        // We need to check if the rest of tokens are only right parenthses.
        // We are going to rule out redundant parentheses.
        if ($this->token?->getType() !== TokenType::Separator
            || $this->token?->getValue() !== ')'
            || $this->parenthesesDepth != 0) {
            return;
        }

        // Check if the rest of tokens are only right parentheses.
        $isOnlyRightParentheses = true;
        $tokenCount = $this->tokens->count();
        for ($i = $this->position + 1; $i < $tokenCount && $isOnlyRightParentheses; $i++) {
            $token = $this->tokens[$i];
            if ($token->getType() === TokenType::Separator && $token->getValue() === ')') {
                continue;
            }

            $isOnlyRightParentheses = false;
        }

        // If the rest of tokens are only right parentheses, we are going to rule out redundant parentheses.
        if (!$isOnlyRightParentheses) {
            throw new SyntaxException('Unexpected right parentheses');
        }

        $this->position = $tokenCount;
        $this->token = null;
        $this->nextToken = null;
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
                $nextExpression = $this->parseExpression();

                $expression = new BinaryExpressionSyntaxNode(Operator::create($operator), $expression, $nextExpression);
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
            'ast' => $this->ast,
        ]));
    }
}
