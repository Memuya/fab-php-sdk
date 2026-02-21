<?php

namespace Memuya\Fab\Readers\Json\Filters;

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
     * Apply the filter to the data item.
     *
     * @param array<string, mixed> $item
     * @param array<string, mixed> $filters
     * @return bool
     */
    public function applyTo(array $item, array $filters): bool;
}
