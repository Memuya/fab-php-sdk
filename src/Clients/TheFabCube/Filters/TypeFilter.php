<?php

namespace Memuya\Fab\Clients\TheFabCube\Filters;

use Memuya\Fab\Clients\File\Filters\Filterable;

class TypeFilter implements Filterable
{
    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['types']) && ! is_null($filters['types']);
    }

    /**
     * @inheritDoc
     */
    public function applyTo(array $data, array $filters): array
    {
        return array_filter($data, function ($card) use ($filters) {
            $cardTypes = array_map(fn($type) => strtolower($type), $card['types']);
            $filterTypes = array_map(fn($type) => strtolower($type), $filters['types']);

            return count(array_intersect($cardTypes, $filterTypes)) !== 0;
        });
    }
}