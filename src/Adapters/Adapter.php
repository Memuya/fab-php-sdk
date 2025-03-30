<?php

namespace Memuya\Fab\Adapters;

interface Adapter
{
    /**
     * Return a filtered list of cards.
     *
     * @param array<string, mixed> $filters
     * @return mixed
     */
    public function getCards(array $filters = []): mixed;

    /**
     * Return information on a card.
     *
     * @param string $identifier
     * @param string $key
     * @return mixed
     */
    public function getCard(string $identifier, string $key): mixed;
}
