<?php

namespace CalcTek\Calculator\Parser\Nodes;

/**
 * A node that represents a literal value
 */
class LiteralSyntaxNode extends SyntaxNode
{
    /**
     * @var string The value of the literal
     */
    private string $value;

    /**
     * @param string $value The value of the literal
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string The value of the literal
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
