<?php

namespace CalcTek\Calculator\Parser;

enum Operator: int
{
    case Plus = 0;
    case Minus = 1;
    case Multiply = 2;
    case Divide = 3;

    /**
     * @throws SyntaxException
     */
    public static function create(string $value): Operator
    {
        return match ($value) {
            '+' => Operator::Plus,
            '-' => Operator::Minus,
            '*' => Operator::Multiply,
            '/' => Operator::Divide,
            default => throw new SyntaxException("Invalid operator: $value")
        };
    }
}
