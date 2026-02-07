<?php

namespace Memuya\Fab\Adapters\TheFabCube\Filters;

use Memuya\Fab\Adapters\TheFabCube\Filters\Support\FiltersData;
use Memuya\Fab\Readers\Json\Filters\Filterable;

class InteractsWithKeywordsFilter implements Filterable
{
    use FiltersData;

    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['interacts_with_keywords']) && is_array($filters['interacts_with_keywords']);
    }

    /**
     * @inheritDoc
     */
    public function applyTo(array $data, array $filters): array
    {
        return array_filter($data, function ($card) use ($filters) {
            return $this->filterIntersectsWithData(
                data: $card,
                filters: $filters,
                dataKey: 'interacts_with_keywords',
                filterKey: 'interacts_with_keywords',
            );
        });
    }
}
