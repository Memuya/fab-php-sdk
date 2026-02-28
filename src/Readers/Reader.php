<?php

namespace Memuya\Fab\Readers;

use ReflectionException;

interface Reader
{
    /**
     * Read and filter card data from a file.
     *
     * @param SearchCriteria $searchCriteria
     * @return array<string, mixed>
     * @throws ReflectionException
     */
    public function searchData(SearchCriteria $searchCriteria): array;
}
