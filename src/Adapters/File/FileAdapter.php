<?php

namespace Memuya\Fab\Adapters\File;

use ReflectionClass;
use RuntimeException;
use SplObjectStorage;
use Memuya\Fab\Adapters\Adapter;
use Memuya\Fab\Adapters\SearchCriteria;
use Memuya\Fab\Adapters\File\Filters\Filterable;
use Memuya\Fab\Exceptions\SearchCriteriaNotRegisteredException;

class FileAdapter implements Adapter
{
    /**
     * The location of the JSON file.
     *
     * @var string
     */
    private string $filepath;

    /**
     * A list of all filters that can be applied.
     *
     * @var array<Filterable>
     */
    private array $filters = [];

    /**
     * The registered criteria classes for each criteria type.
     * Example:
     * [
     *     SearchCriteriaType::MultiCard => CardsConfig::class,
     *     SearchCriteriaType::SingleCard => CardConfig::class,
     * ]
     *
     * @var SplObjectStorage<SearchCriteriaType, class-string>
     */
    private SplObjectStorage $registeredSearchCriteria;

    /**
     * @param string $filepath
     * @param array<Filterable> $filters
     */
    public function __construct(string $filepath, array $filters = [])
    {
        $this->filepath = $filepath;
        $this->registeredSearchCriteria = new SplObjectStorage();

        $this->registerFilters($filters);
    }

    /**
     * Append to the existing list of filters.
     *
     * @param array<Filterable> $filters
     * @return void
     */
    public function registerFilters(array $filters): void
    {
        $filters = array_filter(
            $filters,
            fn($filter): bool => $filter instanceof Filterable,
        );

        $this->filters = array_merge($this->filters, $filters);
    }

    /**
     * Register the config
     *
     * @param SearchCriteriaType $type
     * @param class-string $config
     * @return void
     */
    public function registerConfig(SearchCriteriaType $type, string $config): void
    {
        $this->registeredSearchCriteria[$type] = $config;
    }

    /**
     * @inheritDoc
     */
    public function getCards(array $filters = []): array
    {
        return $this->filterList(
            $this->resolveSearchCriteria(SearchCriteriaType::MultiCard, $filters),
        );
    }

    /**
     * @inheritDoc
     */
    public function getCard(string $identifier, string $key = 'name'): array
    {
        return $this->filterList(
            $this->resolveSearchCriteria(SearchCriteriaType::SingleCard, [$key => $identifier]),
        )[0] ?? [];
    }

    /**
     * Read and filter cards from the registered JSON file.
     *
     * @param SearchCriteria $config
     * @return array<string, mixed>
     */
    public function filterList(SearchCriteria $config): array
    {
        $cards = $this->readFileToJson();
        $filters = $config->getParameterValues();

        /** @var Filterable $filter */
        foreach ($this->filters as $filter) {
            if ($filter->canResolve($filters)) {
                $cards = $filter->applyTo($cards, $filters);
            }
        }

        // Reset array keys.
        return array_values($cards);
    }

    /**
     * Read the file into a local JSON array.
     *
     * @return array<string, mixed>
     * @throws RuntimeException
     */
    private function readFileToJson(): array
    {
        if (! file_exists($this->filepath)) {
            throw new RuntimeException('File not found.');
        }

        return json_decode(file_get_contents($this->filepath), true);
    }

    /**
     * Check to see if the given criteria type has been registered.
     *
     * @param SearchCriteriaType $type
     * @return bool
     */
    private function isSearchCriteriaRegisteredFor(SearchCriteriaType $type): bool
    {
        return isset($this->registeredSearchCriteria[$type]);
    }

    /**
     * Resolve the search criteria object needed for the given type.
     *
     * @param SearchCriteriaType $type
     * @param array<string, mixed> $filters
     * @return SearchCriteria
     * @throws SearchCriteriaNotRegisteredException
     */
    private function resolveSearchCriteria(SearchCriteriaType $type, array $filters): SearchCriteria
    {
        if (! $this->isSearchCriteriaRegisteredFor($type)) {
            throw new SearchCriteriaNotRegisteredException("Search criteria not registerd for {$type->name}.");
        }

        $reflection = new ReflectionClass($this->registeredSearchCriteria[$type]);

        return $reflection->newInstance($filters);
    }
}
