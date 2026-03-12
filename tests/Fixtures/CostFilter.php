<?php

namespace Tests\Fixtures;

use Memuya\Fab\Readers\Json\Filters\Filterable;

class CostFilter implements Filterable
{
    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['cost']);
    }

    /**
     * @inheritDoc
     */
    public function applyTo(array $item, array $filters): bool
    {
        return str_contains($item['cost'], $filters['cost']);
    }
}
