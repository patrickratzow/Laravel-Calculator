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
     * @var IdentifierSyntaxNode $identifier The identifier of the function
     */
    private IdentifierSyntaxNode $identifier;

    /**
     * @var Collection<SyntaxNode> The arguments of the function
     */
    private Collection $arguments;

    /**
     * @param IdentifierSyntaxNode $identifier The identifier of the function
     * @param Collection<SyntaxNode> $arguments The arguments of the function
     */
    public function __construct(IdentifierSyntaxNode $identifier, Collection $arguments)
    {
        $this->identifier = $identifier;
        $this->arguments = $arguments;
    }

    /**
     * @return IdentifierSyntaxNode The identifier of the function
     */
    public function getIdentifier(): IdentifierSyntaxNode
    {
        return $this->identifier;
    }

    /**
     * @return Collection<SyntaxNode> The arguments of the function
     */
    public function getArguments(): Collection
    {
        return $this->arguments;
    }
}
