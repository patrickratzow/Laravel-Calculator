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
     * @throws SyntaxException Thrown if the input is invalid
     */
    public function parse(): SyntaxNode
    {
        $this->token = $this->tokens[0];
        $this->nextToken = $this->peek();
        $ast = $this->parseExpression();
        $this->skipRedundantParentheses();
        $this->ast = $ast;

        // Current token should be null after we are done parsing
        if (!is_null($this->token)) {
            throw new SyntaxException('Expected end of input');
        }

        return $this->ast;
    }

    /**
     * @throws SyntaxException
     */
    private function parseExpression(): ?SyntaxNode
    {
        $expression = null;
        if ($this->token?->getType() === TokenType::Separator && $this->token?->getValue() === '(') {
            $expression = $this->parseParentheses();
        }
        if ($this->token?->getType() === TokenType::Operator) {
            if ($this->token->getValue() === '-') {
                $expression = $this->parseUnaryExpression();
            } else {
                // Only -5 is valid, +5 is not a valid expression
                throw new SyntaxException('Unexpected operator before an unary expression');
            }
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
    private function parseUnaryExpression(): ?SyntaxNode
    {
        if (!$this->consume(new Token(TokenType::Operator, '-'))) {
            return null;
        }

        // If the next token is an operator, we are inside a binary expression
        if ($this->nextToken?->getType() === TokenType::Operator) {
            $literal = new LiteralSyntaxNode($this->token->getValue());
            $this->next();
            $unary = new UnaryExpressionSyntaxNode(Operator::Minus, $literal);

            return $this->parseBinaryExpression($unary);
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
    private function parseCallExpression(IdentifierSyntaxNode $identifier): SyntaxNode
    {
        if (!$this->consume(new Token(TokenType::Separator, '('))) {
            throw new SyntaxException('Expected ( after function identifier');
        }

        $expression = $this->parseExpression();

        // Deal with cases where we can't seem to parse it to an expression
        if (is_null($expression)) {
            // We are at the end of the input, we are missing a )
            if (is_null($this->nextToken)) {
                // If the previous token is a (, and the current token is a ), we are missing arguments
                if ($this->token?->getType() == TokenType::Separator
                    && $this->token?->getValue() == ')'
                    && $this->previousToken?->getType() == TokenType::Separator
                    && $this->previousToken?->getValue() == '(') {
                    throw new SyntaxException('Expected at least 1 argument for a function call');
                }

                throw new SyntaxException('Expected ) after function arguments');
            }

            // We can't parse an expression, so we can't parse the arguments
            throw new SyntaxException('Unable to parse function arguments');
        }

        if (!$this->consume(new Token(TokenType::Separator, ')'))) {
            throw new SyntaxException('Expected ) after function arguments');
        }

        $expression = new CallExpressionSyntaxNode($identifier, $expression);
        if ($this->token?->getType() === TokenType::Operator) {
            $expression = $this->parseBinaryExpression($expression);
        }

        return $expression;
    }

    /**
     * @throws SyntaxException
     */
    private function parseParentheses(): ?SyntaxNode
    {
        $isCurrentTokenOpeningParentheses = $this->token?->getType() === TokenType::Separator
            && $this->token?->getValue() === '(';
        if (!$isCurrentTokenOpeningParentheses || !$this->consume(new Token(TokenType::Separator, '('))) {
            return null;
        }

        $this->parenthesesDepth++;
        $expression = $this->parseExpression();
        $this->parenthesesDepth--;

        // TODO: Is this the right thing to do? Something like (2+2 is syntactically invalid, but not semantically.
        // TODO: Look into if we can be more lenient here & just purge the parentheses from the AST
        if (!$this->consume(new Token(TokenType::Separator, ')'))) {
            throw new SyntaxException('Unclosed parentheses. Please close your parentheses');
        }

        // After the end of the parentheses, we want to look out for possible binary expressions
        if ($this->token?->getType() == TokenType::Operator
            && is_a($expression,BinaryExpressionSyntaxNode::class)) {
            $expression = $this->parseBinaryExpression($expression);
        }

        return $expression;
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

        // Set the position to the end of tokens.
        $this->position = $tokenCount;
        $this->token = null;
        $this->nextToken = null;
    }

    /**
     * @param SyntaxNode $leftExpression
     * @return BinaryExpressionSyntaxNode|LiteralSyntaxNode
     * @throws SyntaxException
     */
    public function parseBinaryExpression(SyntaxNode $leftExpression): BinaryExpressionSyntaxNode|null
    {
        $operator = Operator::create($this->token->getValue());
        // We skip the current the operator here
        $this->next();
        $rightExpression = $this->parseExpression();
        if (is_null($rightExpression)) {
            throw new SyntaxException('Expected expression after operator');
        }

        return new BinaryExpressionSyntaxNode($operator, $leftExpression, $rightExpression);
    }

    /**
     * @throws SyntaxException
     */
    private function parseLiteral(): ?SyntaxNode
    {
        $expression = null;
        if ($this->token?->getType() !== TokenType::Literal) {
            return $expression;
        }

        $expression = new LiteralSyntaxNode($this->token->getValue());
        if ($this->nextToken?->getType() === TokenType::Literal) {
            throw new SyntaxException('Invalid token, 2 literals in a row');
        }
        if ($this->nextToken?->getType() == TokenType::Separator && $this->nextToken?->getValue() == '(') {
            throw new SyntaxException('Unexpected open parentheses immediately after a literal');
        }

        $this->next();
        if ($this->token?->getType() === TokenType::Operator) {
            $binaryExpression = $this->parseBinaryExpression($expression);
            if (!is_null($binaryExpression)) {
                $expression = $binaryExpression;
            }
        }

        return $expression;
    }

    /**
     * @throws SyntaxException
     */
    private function parseIdentifier(): IdentifierSyntaxNode
    {
        $name = $this->token->getValue();
        $function = Functions::tryFrom($name);
        if (is_null($function)) {
            throw new SyntaxException("$name is not a valid function");
        }

        $identifier = new IdentifierSyntaxNode($name);
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
            'ast' => $this->ast ?? collect()
        ]));
    }
}
