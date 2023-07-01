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
     * @param string $value The text value of the node
     * @param SyntaxNode $operand The operand of the unary expression
     */
    public function __construct(string $value, SyntaxNode $operand)
    {
        parent::__construct($value);

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
