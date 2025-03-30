<?php

namespace Memuya\Fab\Adapters\TheFabCube\Endpoints\Card;

use Memuya\Fab\Adapters\Config;
use Memuya\Fab\Attributes\Parameter;

class CardConfig extends Config
{
    /**
     * Name to search with.
     *
     * @var string
     */
    #[Parameter]
    public string $name;
}
