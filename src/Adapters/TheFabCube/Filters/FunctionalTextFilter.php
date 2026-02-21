<?php

namespace Memuya\Fab\Adapters\TheFabCube\Filters;

use Memuya\Fab\Readers\Json\Filters\Filterable;

class FunctionalTextFilter implements Filterable
{
    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['functional_text']);
    }

    /**
     * @inheritDoc
     */
    public function applyTo(array $item, array $filters): bool
    {
        return str_contains(strtolower($item['functional_text']), strtolower($filters['functional_text']));
    }
}
