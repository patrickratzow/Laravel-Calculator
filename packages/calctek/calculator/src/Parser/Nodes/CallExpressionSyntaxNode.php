<?php

namespace CalcTek\Calculator\Parser\Nodes;

use Illuminate\Support\Collection;

/**
 * A node that represents a function call expression
 * This would be the sqrt() part of sqrt(4+2)
 */
class CallExpressionSyntaxNode extends SyntaxNode
{
    /**
     * @var Collection<SyntaxNode> The arguments of the function
     */
    private Collection $arguments;

    /**
     * @param string $value The text value of the node
     * @param Collection<SyntaxNode> $arguments The arguments of the function
     */
    public function __construct(string $value, Collection $arguments)
    {
        parent::__construct($value);

        $this->arguments = $arguments;
    }

    /**
     * @return Collection<SyntaxNode> The arguments of the function
     */
    public function getArguments(): Collection
    {
        return $this->arguments;
    }
}
