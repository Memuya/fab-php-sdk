<?php

namespace Memuya\Fab\Adapters\File;

enum ConfigType
{
    case MultiCard; // Adapter::getCards()
    case SingleCard; // Adapter::getCard()
}
