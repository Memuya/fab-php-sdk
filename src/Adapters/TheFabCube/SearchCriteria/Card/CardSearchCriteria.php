<?php

namespace Memuya\Fab\Adapters\TheFabCube\SearchCriteria\Card;

use Memuya\Fab\Adapters\SearchCriteria;
use Memuya\Fab\Attributes\Parameter;

class CardSearchCriteria extends SearchCriteria
{
    /**
     * Name to search with.
     *
     * @var string
     */
    #[Parameter]
    public string $name;
}
