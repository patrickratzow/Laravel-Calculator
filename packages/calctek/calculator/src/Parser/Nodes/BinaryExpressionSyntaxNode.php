<?php

namespace CalcTek\Calculator\Parser\Nodes;

/**
 * A node that represents a binary expression
 */
class BinaryExpressionSyntaxNode extends SyntaxNode
{
    /**
     * @var SyntaxNode The left operand of the binary expression
     */
    private SyntaxNode $left;

    /**
     * @var SyntaxNode The right operand of the binary expression
     */
    private SyntaxNode $right;

    /**
     * @param string $value The text value of the node
     * @param SyntaxNode $left The left operand of the binary expression
     * @param SyntaxNode $right The right operand of the binary expression
     */
    public function __construct(string $value, SyntaxNode $left, SyntaxNode $right)
    {
        parent::__construct($value);

        $this->left = $left;
        $this->right = $right;
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
