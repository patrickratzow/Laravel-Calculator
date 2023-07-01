<?php

namespace CalcTek\Calculator\Parser\Nodes;

/**
 * A node that represents a unary expression
 */
class UnaryExpressionSyntaxNode extends SyntaxNode
{
    /**
     * @var SyntaxNode The operand of the unary expression
     */
    private SyntaxNode $operand;

    /**
     * @param SyntaxNode $operand The operand of the unary expression
     */
    public function __construct(SyntaxNode $operand)
    {
        $this->operand = $operand;
    }

    /**
     * @return SyntaxNode The operand of the unary expression
     */
    public function getOperand(): SyntaxNode
    {
        return $this->operand;
    }
}
