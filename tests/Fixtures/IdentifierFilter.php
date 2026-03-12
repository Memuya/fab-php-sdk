<?php

namespace Tests\Fixtures;

use Memuya\Fab\Readers\Json\Filters\Filterable;

class IdentifierFilter implements Filterable
{
    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['identifier']);
    }

    /**
     * @inheritDoc
     */
    public function applyTo(array $item, array $filters): bool
    {
        return str_contains($item['identifier'], $filters['identifier']);
    }
}
