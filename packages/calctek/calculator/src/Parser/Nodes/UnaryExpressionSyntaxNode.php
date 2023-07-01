<?php

namespace CalcTek\Calculator\Parser\Nodes;

use CalcTek\Calculator\Parser\Operator;

/**
 * A node that represents a unary expression
 */
class UnaryExpressionSyntaxNode extends SyntaxNode
{
    /**
     * @var string The operator value of the node
     */
    private Operator $operator;

    /**
     * @var SyntaxNode The operand of the unary expression
     */
    private SyntaxNode $operand;

    /**
     * @param Operator $operator The operator value of the node
     * @param SyntaxNode $operand The operand of the unary expression
     */
    public function __construct(Operator $operator, SyntaxNode $operand)
    {
        $this->operator = $operator;
        $this->operand = $operand;
    }

    /**
     * @return string The operator value of the node
     */
    public function getOperator(): Operator
    {
        return $this->operator;
    }

    /**
     * @return SyntaxNode The operand of the unary expression
     */
    public function getOperand(): SyntaxNode
    {
        return $this->operand;
    }
}
