<?php

namespace Memuya\Fab\Adapters\TheFabCube\Filters;

use Memuya\Fab\Adapters\File\Filters\Filterable;

class BlitzLegalFilter implements Filterable
{
    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['blitz_legal']) && ! is_null($filters['blitz_legal']);
    }

    /**
     * @inheritDoc
     */
    public function applyTo(array $data, array $filters): array
    {
        return array_filter($data, function ($card) use ($filters) {
            return $card['blitz_legal'] === $filters['blitz_legal'];
        });
    }
}
