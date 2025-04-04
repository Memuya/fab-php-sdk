<?php

namespace Memuya\Fab\Adapters\TheFabCube\SearchCriteria\Card;

use Memuya\Fab\Attributes\Parameter;
use Memuya\Fab\Adapters\SearchCriteria;

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
