<?php

namespace Memuya\Fab\Adapters\File;

enum SearchCriteriaType
{
    case MultiCard; // Adapter::getCards()
    case SingleCard; // Adapter::getCard()
}
