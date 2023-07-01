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
     * @var SyntaxNode The arguments of the function
     */
    private SyntaxNode $argument;

    /**
     * @param IdentifierSyntaxNode $identifier The identifier of the function
     * @param SyntaxNode $arguments The argument of the function
     */
    public function __construct(IdentifierSyntaxNode $identifier, SyntaxNode $argument)
    {
        $this->identifier = $identifier;
        $this->argument = $argument;
    }

    /**
     * @return IdentifierSyntaxNode The identifier of the function
     */
    public function getIdentifier(): IdentifierSyntaxNode
    {
        return $this->identifier;
    }

    /**
     * @return SyntaxNode The argument of the function
     */
    public function getArgument(): SyntaxNode
    {
        return $this->argument;
    }
}
