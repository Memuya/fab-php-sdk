<?php

namespace Memuya\Fab\Adapters;

interface Adapter
{
    /**
     * Return a filtered list of cards.
     *
     * @param SearchCriteria $searchCriteria
     * @return array<string, mixed>
     */
    public function getCards(SearchCriteria $searchCriteria): array;
}
