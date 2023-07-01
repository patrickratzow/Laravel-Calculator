<?php

namespace CalcTek\Calculator\Parser\Nodes;

/**
 * A node that represents an identifier
 */
class IdentifierSyntaxNode extends SyntaxNode
{
    /**
     * @var string The name of the identifier
     */
    private string $name;

    /**
     * @param string $name The name of the identifier
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string The name of the identifier
     */
    public function getName(): string
    {
        return $this->name;
    }
}
