<?php

namespace CalcTek\Calculator\Parser\Nodes;

/**
 * A node that represents a binary expression
 */
class BinaryExpressionSyntaxNode extends SyntaxNode
{
    /**
     * @var string The operator value of the node
     */
    private string $operator;

    /**
     * @var SyntaxNode The left operand of the binary expression
     */
    private SyntaxNode $left;

    /**
     * @var SyntaxNode The right operand of the binary expression
     */
    private SyntaxNode $right;

    /**
     * @param string $operator The text value of the node
     * @param SyntaxNode $left The left operand of the binary expression
     * @param SyntaxNode $right The right operand of the binary expression
     */
    public function __construct(string $operator, SyntaxNode $left, SyntaxNode $right)
    {
        $this->operator = $operator;
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * @return string The operator value of the node
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @return SyntaxNode The left operand of the binary expression
     */
    public function getLeft(): SyntaxNode
    {
        return $this->left;
    }

    /**
     * @return SyntaxNode The right operand of the binary expression
     */
    public function getRight(): SyntaxNode
    {
        return $this->right;
    }
}
