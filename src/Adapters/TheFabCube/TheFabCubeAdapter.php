<?php

namespace Memuya\Fab\Adapters\TheFabCube;

use ReflectionException;
use Memuya\Fab\Readers\Reader;
use Memuya\Fab\Adapters\Entity;
use Memuya\Fab\Adapters\Adapter;
use Memuya\Fab\Readers\SearchCriteria;
use Memuya\Fab\Adapters\TheFabCube\Entities\Card;

/**
 * The FAB Cube is a Git repo that store an up-to-date list of all Flesh and Blood cards.
 * This adapter is intended to be used with the JSON/CSV file located in the repo link below.
 *
 * @note If you are using the CSV file, please be sure to convert the header column values to
 * lowercase snake case so it is compatible with the search criteria. e.g. "Unique IO" -> "unique_id".
 *
 * @link https://github.com/the-fab-cube/flesh-and-blood-cards
 * @link https://raw.githubusercontent.com/the-fab-cube/flesh-and-blood-cards/refs/heads/develop/json/english/card.json
 * @link https://raw.githubusercontent.com/the-fab-cube/flesh-and-blood-cards/refs/heads/develop/csvs/english/card.csv
 */
class TheFabCubeAdapter implements Adapter
{
    /**
     * The entity class to map the results to.
     *
     * @var ?class-string<Entity>
     */
    private ?string $entity = null;

    public function __construct(private readonly Reader $fileReader) {}

    /**
     * @inheritDoc
     * @return list<Card>
     * @throws ReflectionException
     */
    public function getCards(SearchCriteria $searchCriteria): array
    {
        $cards = $this->fileReader->searchData($searchCriteria);

        if (! $this->entity) {
            return $cards;
        }

        return array_map(
            fn(array $card): Entity => new ($this->entity)($card),
            $cards,
        );
    }

    /**
     * Map the results to an entity class.
     *
     * @param class-string<Entity> $entity
     * @return self
     */
    public function mapTo(string $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Return the underlying Reader object.
     *
     * @return Reader
     */
    public function getFileReader(): Reader
    {
        return $this->fileReader;
    }
}
