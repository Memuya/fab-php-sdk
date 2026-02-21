<?php

namespace Memuya\Fab\Adapters\TheFabCube\Filters;

use Memuya\Fab\Readers\Json\Filters\Filterable;
use Memuya\Fab\Adapters\TheFabCube\Filters\Support\FiltersData;

class RemovedKeywordsFilter implements Filterable
{
    use FiltersData;

    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['removed_keywords']) && is_array($filters['removed_keywords']);
    }

    /**
     * @inheritDoc
     */
    public function applyTo(array $item, array $filters): bool
    {
        return $this->filterIntersectsWithData(
            data: $item,
            filters: $filters,
            dataKey: 'removed_keywords',
            filterKey: 'removed_keywords',
        );
    }
}
