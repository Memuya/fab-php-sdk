<?php

namespace Memuya\Fab\Adapters\TheFabCube\Filters;

use Memuya\Fab\Readers\Json\Filters\Filterable;

class LlLegal implements Filterable
{
    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['ll_legal']);
    }

    /**
     * @inheritDoc
     */
    public function applyTo(array $item, array $filters): bool
    {
        return $item['ll_legal'] === $filters['ll_legal'];
    }
}
