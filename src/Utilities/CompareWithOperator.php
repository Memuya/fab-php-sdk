<?php

namespace Memuya\Fab\Utilities;

use Memuya\Fab\Utilities\Extract\Value;
use Memuya\Fab\Enums\ComparisonOperator;

/**
 * @template T
 */
class CompareWithOperator
{
    public function __construct(
        /** @var T */
        public mixed $value,
        public ComparisonOperator $operator = ComparisonOperator::Equals,
    ) {}

    public function compare(mixed $input): bool
    {
        $value = Value::from($this->value)->extract();

        return match ($this->operator) {
            ComparisonOperator::NotEquals => $input !== $value,
            ComparisonOperator::GreaterThan => $input > $value,
            ComparisonOperator::LessThan => $input < $value,
            ComparisonOperator::Gte => $input >= $value,
            ComparisonOperator::Lte => $input <= $value,
            default => $input === $value,
        };
    }
}
