<?php

namespace Memuya\Fab\Enums;

enum ComparisonOperator: string
{
    case Equals = '=';
    case NotEquals = '!=';
    case GreaterThan = '>';
    case LessThan = '<';
    case Gte = '>=';
    case Lte = '<=';
}
