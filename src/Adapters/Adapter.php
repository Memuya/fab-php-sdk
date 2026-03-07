<?php

namespace Memuya\Fab\Adapters;

use Memuya\Fab\Readers\SearchCriteria;

interface Adapter
{
    /**
     * Return a filtered list of cards.
     *
     * @param SearchCriteria $searchCriteria
     * @return list<array<string, mixed>>
     */
    public function getCards(SearchCriteria $searchCriteria): array;
}
