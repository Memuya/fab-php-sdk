<?php

namespace Memuya\Fab\Adapters\TheFabCube\Filters;

use Memuya\Fab\Adapters\File\Filters\Filterable;

class TypeTextFilter implements Filterable
{
    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['type_text']) && ! is_null($filters['type_text']);
    }

    /**
     * @inheritDoc
     */
    public function applyTo(array $data, array $filters): array
    {
        return array_filter($data, function ($card) use ($filters) {
            return $card['type_text'] === $filters['type_text'];
        });
    }
}
