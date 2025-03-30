<?php

namespace Memuya\Fab\Adapters\File\Filters;

interface Filterable
{
    /**
     * Determine if the filter can be applied.
     *
     * @param array<string, mixed> $filters
     * @return bool
     */
    public function canResolve(array $filters): bool;

    /**
     * Apply the filter to the query.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $filter
     * @return array<string, mixed>
     */
    public function applyTo(array $data, array $filters): array;
}
