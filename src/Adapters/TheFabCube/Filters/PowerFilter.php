<?php

namespace Memuya\Fab\Adapters\TheFabCube\Filters;

use Memuya\Fab\Readers\Json\Filters\Filterable;

class PowerFilter implements Filterable
{
    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['power']);
    }

    /**
     * @inheritDoc
     */
    public function applyTo(array $item, array $filters): bool
    {
        return $item['power'] === $filters['power'];
    }
}
