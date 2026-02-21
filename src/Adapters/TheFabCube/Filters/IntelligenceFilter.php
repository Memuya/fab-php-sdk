<?php

namespace Memuya\Fab\Adapters\TheFabCube\Filters;

use Memuya\Fab\Readers\Json\Filters\Filterable;

class IntelligenceFilter implements Filterable
{
    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['intelligence']);
    }

    /**
     * @inheritDoc
     */
    public function applyTo(array $item, array $filters): bool
    {
        return $item['intelligence'] === $filters['intelligence'];
    }
}
;
