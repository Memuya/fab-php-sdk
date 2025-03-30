<?php

namespace Memuya\Fab\Adapters\TheFabCube\Filters;

use Memuya\Fab\Adapters\File\Filters\Filterable;

class CcLegalFilter implements Filterable
{
    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['commoner_legal']) && ! is_null($filters['commoner_legal']);
    }

    /**
     * @inheritDoc
     */
    public function applyTo(array $data, array $filters): array
    {
        return array_filter($data, function ($card) use ($filters) {
            return $card['commoner_legal'] === $filters['commoner_legal'];
        });
    }
}
