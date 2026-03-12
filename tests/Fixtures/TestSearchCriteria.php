<?php

namespace Tests\Fixtures;

use Memuya\Fab\Attributes\Filter;
use Memuya\Fab\Readers\SearchCriteria;

class TestSearchCriteria extends SearchCriteria
{
    #[Filter(IdentifierFilter::class)]
    public string $identifier;

    #[Filter(CostFilter::class)]
    public string $cost;
}