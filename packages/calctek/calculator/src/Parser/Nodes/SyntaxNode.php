<?php

namespace CalcTek\Calculator\Parser\Nodes;

/**
 * The base class for all syntax nodes
 */
abstract class SyntaxNode
{
    /**
     * @var string The text value of the node
     */
    private string $value;

    /**
     * @param string $value The text value of the node
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string The text value of the node
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
