<?php

namespace Memuya\Fab\Adapters\TheFabCube;

use Memuya\Fab\Adapters\Adapter;
use Memuya\Fab\Adapters\SearchCriteria;
use Memuya\Fab\Adapters\File\FileAdapter;
use Memuya\Fab\Adapters\TheFabCube\Filters;
use Memuya\Fab\Adapters\File\Filters\Filterable;
use Memuya\Fab\Adapters\TheFabCube\Entities\Card;

/**
 * The FAB Cube is a Git repo that store an up-to-date list of all Flesh and Blood cards.
 * This adapter is intended to be used with the JSON file located in the repo link below.
 *
 * @link https://github.com/the-fab-cube/flesh-and-blood-cards
 * @link https://raw.githubusercontent.com/the-fab-cube/flesh-and-blood-cards/refs/heads/develop/json/english/card.json
 */
class TheFabCubeAdapter implements Adapter
{
    private FileAdapter $fileAdapter;

    /**
     * @param string $filepath
     * @param array<Filterable> $filters
     */
    public function __construct(string $filepath, array $filters = [])
    {
        $this->fileAdapter = new FileAdapter(
            $filepath,
            $filters ?: $this->getDefaultFilters(),
        );
    }

    /**
     * @inheritDoc
     * @return array<Card>
     */
    public function getCards(SearchCriteria $searchCriteria): array
    {
        $cards = $this->fileAdapter->getCards($searchCriteria);

        return array_map(
            fn(array $card): Card => new Card($card),
            $cards,
        );
    }

    /**
     * Register filters that are usable when querying from the file.
     *
     * @param array<Filterable> $filters
     * @return void
     */
    public function registerFilters(array $filters): void
    {
        $this->getFileAdapter()->registerFilters($filters);
    }

    /**
     * Return the underlying FileAdapter object.
     *
     * @return FileAdapter
     */
    public function getFileAdapter(): FileAdapter
    {
        return $this->fileAdapter;
    }

    /**
     * Return a list of filters to be registered if none are provided.
     *
     * @return array<Filterable>
     */
    private function getDefaultFilters(): array
    {
        return [
            new Filters\AbilitiesAndEffectsFilter(),
            new Filters\AbilitiesAndEffectsKeywordsFilter(),
            new Filters\ArcaneFilter(),
            new Filters\BlitzBannedFilter(),
            new Filters\BlitzLegalFilter(),
            new Filters\BlitzLivingLegendFilter(),
            new Filters\BlitzSuspendedFilter(),
            new Filters\CardIdFilter(),
            new Filters\CardKeywordsFilter(),
            new Filters\CcBannedFilter(),
            new Filters\CcLegalFilter(),
            new Filters\CcLivingLegendFilter(),
            new Filters\CcSuspendedFilter(),
            new Filters\CommonerBannedFilter(),
            new Filters\CommonerLegalFilter(),
            new Filters\CommonerSuspendedFilter(),
            new Filters\CostFilter(),
            new Filters\DefenseFilter(),
            new Filters\FunctionalTextFilter(),
            new Filters\FunctionalTextPlainFilter(),
            new Filters\GrantedKeywordsFilter(),
            new Filters\HealthFilter(),
            new Filters\IntelligenceFilter(),
            new Filters\InteractsWithKeywordsFilter(),
            new Filters\LlBannedFilter(),
            new Filters\LlLegal(),
            new Filters\LlRestrictedFilter(),
            new Filters\NameFilter(),
            new Filters\PitchFilter(),
            new Filters\PlayedHorizontallyFilter(),
            new Filters\PowerFilter(),
            new Filters\RarityFilter(),
            new Filters\RemovedKeywordsFilter(),
            new Filters\SetFilter(),
            new Filters\TypeFilter(),
            new Filters\TypeTextFilter(),
            new Filters\UniqueIdFilter(),
            new Filters\UpfBannedFilter(),
        ];
    }
}
