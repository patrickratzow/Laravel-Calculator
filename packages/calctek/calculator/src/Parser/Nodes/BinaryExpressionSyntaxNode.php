<?php

namespace CalcTek\Calculator\Parser\Nodes;

use CalcTek\Calculator\Parser\Operator;

/**
 * A node that represents a binary expression
 */
class BinaryExpressionSyntaxNode extends SyntaxNode
{
    /**
     * @var Operator The operator for the node
     */
    private Operator $operator;

    /**
     * @var SyntaxNode The left operand of the binary expression
     */
    private SyntaxNode $left;

    /**
     * @var SyntaxNode The right operand of the binary expression
     */
    private SyntaxNode $right;

    /**
     * @param Operator $operator The operator for the node
     * @param SyntaxNode $left The left operand of the binary expression
     * @param SyntaxNode $right The right operand of the binary expression
     */
    public function __construct(Operator $operator, SyntaxNode $left, SyntaxNode $right)
    {
        $this->operator = $operator;
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * @return Operator The operator for the node
     */
    public function getOperator(): Operator
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
