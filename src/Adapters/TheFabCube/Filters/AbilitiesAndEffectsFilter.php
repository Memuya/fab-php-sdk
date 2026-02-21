<?php

namespace Memuya\Fab\Adapters\TheFabCube\Filters;

use Memuya\Fab\Readers\Json\Filters\Filterable;
use Memuya\Fab\Adapters\TheFabCube\Filters\Support\FiltersData;

class AbilitiesAndEffectsFilter implements Filterable
{
    use FiltersData;

    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['abilities_and_effects']) && is_array($filters['abilities_and_effects']);
    }

    /**
     * @inheritDoc
     */
    public function applyTo(array $item, array $filters): bool
    {
        return $this->filterIntersectsWithData(
            data: $item,
            filters: $filters,
            dataKey: 'abilities_and_effects',
            filterKey: 'abilities_and_effects',
        );
    }
}
