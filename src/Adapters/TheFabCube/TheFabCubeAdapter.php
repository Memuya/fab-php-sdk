<?php

namespace Memuya\Fab\Adapters\TheFabCube;

use Memuya\Fab\Adapters\Adapter;
use Memuya\Fab\Adapters\SearchCriteria;
use Memuya\Fab\Readers\Json\FileJsonReader;
use Memuya\Fab\Readers\Json\Filters\Filterable;
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
    public function __construct(private FileJsonReader $fileReader)
    {
        $this->fileReader->registerFilters($this->getDefaultFilters());
    }

    /**
     * @inheritDoc
     * @return array<Card>
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
    public function getFileAdapter(): FileJsonReader
    {
        return $this->fileReader;
    }

    /**
     * A list of default filters to be registered with FileJsonReader.
     *
     * @return array<class-string<Filterable>>
     */
    private function getDefaultFilters(): array
    {
        return [
            Filters\AbilitiesAndEffectsFilter::class,
            Filters\AbilitiesAndEffectsKeywordsFilter::class,
            Filters\ArcaneFilter::class,
            Filters\BlitzBannedFilter::class,
            Filters\BlitzLegalFilter::class,
            Filters\BlitzLivingLegendFilter::class,
            Filters\BlitzSuspendedFilter::class,
            Filters\CardIdFilter::class,
            Filters\CardKeywordsFilter::class,
            Filters\CcBannedFilter::class,
            Filters\CcLegalFilter::class,
            Filters\CcLivingLegendFilter::class,
            Filters\CcSuspendedFilter::class,
            Filters\CommonerBannedFilter::class,
            Filters\CommonerLegalFilter::class,
            Filters\CommonerSuspendedFilter::class,
            Filters\CostFilter::class,
            Filters\DefenseFilter::class,
            Filters\FunctionalTextFilter::class,
            Filters\FunctionalTextPlainFilter::class,
            Filters\GrantedKeywordsFilter::class,
            Filters\HealthFilter::class,
            Filters\IntelligenceFilter::class,
            Filters\InteractsWithKeywordsFilter::class,
            Filters\LlBannedFilter::class,
            Filters\LlLegal::class,
            Filters\LlRestrictedFilter::class,
            Filters\NameFilter::class,
            Filters\PitchFilter::class,
            Filters\PlayedHorizontallyFilter::class,
            Filters\PowerFilter::class,
            Filters\RarityFilter::class,
            Filters\RemovedKeywordsFilter::class,
            Filters\SetFilter::class,
            Filters\TypeFilter::class,
            Filters\TypeTextFilter::class,
            Filters\UniqueIdFilter::class,
            Filters\UpfBannedFilter::class,
        ];
    }
}
