<?php

namespace Memuya\Fab\Adapters\TheFabCube\Filters;

use Memuya\Fab\Readers\Json\Filters\Filterable;
use Memuya\Fab\Adapters\TheFabCube\Filters\Support\FiltersData;

class TypeFilter implements Filterable
{
    use FiltersData;

    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['types']) && is_array($filters['types']);
    }

    /**
     * @inheritDoc
     */
    public function applyTo(array $item, array $filters): bool
    {
        return $this->filterIntersectsWithData(
            data: $item,
            filters: $filters,
            dataKey: 'types',
            filterKey: 'types',
        );
    }
}
