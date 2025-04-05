<?php

namespace Memuya\Fab\Adapters\File;

use RuntimeException;
use Memuya\Fab\Adapters\Adapter;
use Memuya\Fab\Adapters\SearchCriteria;
use Memuya\Fab\Adapters\File\Filters\Filterable;

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
     * @param string $filepath
     * @param array<Filterable> $filters
     */
    public function __construct(string $filepath, array $filters = [])
    {
        $this->filepath = $filepath;

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
     * @inheritDoc
     */
    public function getCards(SearchCriteria $criteria): array
    {
        return $this->filterList($criteria);
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
        $criteria = $config->getParameterValues();

        /** @var Filterable $filter */
        foreach ($this->filters as $filter) {
            if ($filter->canResolve($criteria)) {
                $cards = $filter->applyTo($cards, $criteria);
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
}
