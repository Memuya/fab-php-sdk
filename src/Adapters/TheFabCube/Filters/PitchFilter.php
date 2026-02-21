<?php

namespace Memuya\Fab\Adapters\TheFabCube\Filters;

use Memuya\Fab\Utilities\CompareWithOperator;
use Memuya\Fab\Readers\Json\Filters\Filterable;

class PitchFilter implements Filterable
{
    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['pitch']);
    }

    /**
     * @inheritDoc
     */
    public function applyTo(array $item, array $filters): bool
    {
        return $filters['pitch']->compare($item['pitch']);
    }
}
