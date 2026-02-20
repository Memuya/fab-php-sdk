<?php

namespace Memuya\Fab\Adapters\TheFabCube;

use ReflectionException;
use Memuya\Fab\Adapters\Adapter;
use Memuya\Fab\Readers\SearchCriteria;
use Memuya\Fab\Readers\Json\FileJsonReader;
use Memuya\Fab\Adapters\TheFabCube\Entities\Card;

/**
 * The FAB Cube is a Git repo that store an up-to-date list of all Flesh and Blood cards.
 * This adapter is intended to be used with the JSON file located in the repo link below.
 *
 * @link https://github.com/the-fab-cube/flesh-and-blood-cards
 * @link https://raw.githubusercontent.com/the-fab-cube/flesh-and-blood-cards/refs/heads/develop/json/english/card.json
 */
readonly class TheFabCubeAdapter implements Adapter
{
    public function __construct(private FileJsonReader $fileReader) {}

    /**
     * @inheritDoc
     * @return array<Card>
     * @throws ReflectionException
     */
    public function getCards(SearchCriteria $searchCriteria): array
    {
        $cards = $this->fileReader->searchData($searchCriteria);

        return array_map(
            fn(array $card): Card => new Card($card),
            $cards,
        );
    }

    /**
     * Return the underlying FileJsonReader object.
     *
     * @return FileJsonReader
     */
    public function getFileReader(): FileJsonReader
    {
        return $this->fileReader;
    }
}
