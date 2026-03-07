<?php

namespace Memuya\Fab\Readers;

use ReflectionException;

interface Reader
{
    /**
     * Read and filter data from a file.
     *
     * @param SearchCriteria $searchCriteria
     * @return array<string, mixed>
     * @throws ReflectionException
     */
    public function searchFile(SearchCriteria $searchCriteria): array;
}
