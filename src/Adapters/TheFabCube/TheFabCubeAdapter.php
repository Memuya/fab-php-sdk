<?php

namespace Memuya\Fab\Adapters\TheFabCube;

use Memuya\Fab\Adapters\Adapter;
use Memuya\Fab\Adapters\File\FileAdapter;
use Memuya\Fab\Adapters\File\SearchCriteriaType;
use Memuya\Fab\Adapters\File\Filters\Filterable;
use Memuya\Fab\Adapters\TheFabCube\Entities\Card;
use Memuya\Fab\Adapters\TheFabCube\SearchCriteria\Card\CardSearchCriteria;
use Memuya\Fab\Adapters\TheFabCube\SearchCriteria\Cards\CardsSearchCriteria;

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
        $this->fileAdapter->registerConfig(SearchCriteriaType::MultiCard, CardsSearchCriteria::class);
        $this->fileAdapter->registerConfig(SearchCriteriaType::SingleCard, CardSearchCriteria::class);
    }

    /**
     * @inheritDoc
     * @return array<Card>
     */
    public function getCards(array $filters = []): array
    {
        $cards = $this->fileAdapter->getCards($filters);

        return array_map(
            fn(array $card): Card => new Card($card),
            $cards,
        );
    }

    /**
     * @inheritDoc
     * @return Card|null
     */
    public function getCard(string $identifier, string $key = 'name'): ?Card
    {
        $card = $this->fileAdapter->getCard($identifier, $key);

        if (empty($card)) {
            return null;
        }

        return new Card($card);
    }

    /**
     * Register filters can are usable when querying from the file.
     *
     * @param array<Filterable> $filters
     * @return void
     */
    public function registerFilters(array $filters): void
    {
        $this->getFileAdapter()->registerFilters($filters);
    }

    /**
     * Return the underlaying fileAdapter object.
     *
     * @return fileAdapter
     */
    public function getFileAdapter(): fileAdapter
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
            new \Memuya\Fab\Adapters\TheFabCube\Filters\AbilitiesAndEffectsFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\AbilitiesAndEffectsKeywordsFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\ArcaneFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\BlitzBannedFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\BlitzLegalFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\BlitzLivingLegendFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\BlitzSuspendedFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\CardIdFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\CardKeywordsFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\CcBannedFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\CcLegalFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\CcLivingLegendFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\CcSuspendedFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\CommonerBannedFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\CommonerLegalFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\CommonerSuspendedFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\CostFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\DefenseFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\FunctionalTextFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\FunctionalTextPlainFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\GrantedKeywordsFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\HealthFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\IntelligenceFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\InteractsWithKeywordsFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\LlBannedFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\LlLegal(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\LlRestrictedFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\NameFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\PitchFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\PlayedHorizontallyFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\PowerFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\RarityFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\RemovedKeywordsFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\SetFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\TypeFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\TypeTextFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\UniqueIdFilter(),
            new \Memuya\Fab\Adapters\TheFabCube\Filters\UpfBannedFilter(),
        ];
    }
}
