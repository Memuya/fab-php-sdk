<?php

namespace Memuya\Fab\Adapters\TheFabCube\Filters;

use Memuya\Fab\Readers\Json\Filters\Filterable;
use Memuya\Fab\Adapters\TheFabCube\Filters\Support\FiltersData;

class CardKeywordsFilter implements Filterable
{
    use FiltersData;

    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['card_keywords']) && is_array($filters['card_keywords']);
    }

    /**
     * @inheritDoc
     */
    public function applyTo(array $item, array $filters): bool
    {
        return $this->filterIntersectsWithData(
            data: $item,
            filters: $filters,
            dataKey: 'card_keywords',
            filterKey: 'card_keywords',
        );
    }
}
