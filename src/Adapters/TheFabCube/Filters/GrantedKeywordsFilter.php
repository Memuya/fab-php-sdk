<?php

namespace Memuya\Fab\Adapters\TheFabCube\Filters;

use Memuya\Fab\Readers\Json\Filters\Filterable;
use Memuya\Fab\Adapters\TheFabCube\Filters\Support\FiltersData;

class GrantedKeywordsFilter implements Filterable
{
    use FiltersData;

    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['granted_keywords']) && is_array($filters['granted_keywords']);
    }

    /**
     * @inheritDoc
     */
    public function applyTo(array $item, array $filters): bool
    {
        return $this->filterIntersectsWithData(
            data: $item,
            filters: $filters,
            dataKey: 'granted_keywords',
            filterKey: 'granted_keywords',
        );
    }
}
