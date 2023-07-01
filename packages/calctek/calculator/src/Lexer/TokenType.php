<?php

namespace CalcTek\Calculator\Lexer;

enum TokenType: int
{
    case Operator = 0;
    case Literal = 1;
    case Identifier = 2;
    case Separator = 3;
}
