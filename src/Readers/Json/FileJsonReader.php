<?php

namespace Memuya\Fab\Readers\Json;

use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Memuya\Fab\Adapters\SearchCriteria;
use Memuya\Fab\Readers\Json\Filters\Filterable;

class FileJsonReader
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
     * @var array<class-string<Filterable>>
     */
    private array $filters = [];

    /**
     * @param string $filepath
     * @param array<class-string<Filterable>> $filters
     */
    public function __construct(string $filepath, array $filters = [])
    {
        $this->filepath = $filepath;

        $this->registerFilters($filters);
    }

    /**
     * Append to the existing list of filters.
     *
     * @param array<class-string<Filterable>> $filters
     * @return void
     */
    public function registerFilters(array $filters): void
    {
        $this->filters = array_merge($this->filters, $filters);
    }

    /**
     * Read and filter cards from the registered JSON file.
     *
     * @param SearchCriteria $searchCriteria
     * @return array<string, mixed>
     */
    public function searchData(SearchCriteria $searchCriteria): array
    {
        $cards = $this->readFileToJson();
        $criteria = $searchCriteria->getParameterValues();

        foreach ($this->filters as $filterName) {
            try {
                $filter = $this->initialiseFilterClass($filterName);
            } catch (ReflectionException) {
                continue;
            }

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
            throw new RuntimeException("File '{$this->filepath}' not found.");
        }

        return json_decode(file_get_contents($this->filepath), true);
    }

    /**
     * @throws ReflectionException
     */
    private function initialiseFilterClass(string $filter): Filterable
    {
        $reflection = new ReflectionClass($filter);

        if (! $reflection->implementsInterface(Filterable::class)) {
            throw new RuntimeException("Filter '{$filter}' must implement " . Filterable::class);
        }

        /** @var Filterable */
        return $reflection->newInstance();
    }
}
