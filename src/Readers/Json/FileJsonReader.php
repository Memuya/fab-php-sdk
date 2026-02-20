<?php

namespace Memuya\Fab\Readers\Json;

use RuntimeException;
use ReflectionException;
use Memuya\Fab\Adapters\SearchCriteria;

class FileJsonReader
{
    /**
     * The location of the JSON file.
     *
     * @var string
     */
    private string $filepath;

    /**
     * @param string $filepath
     */
    public function __construct(string $filepath)
    {
        $this->filepath = $filepath;
    }

    /**
     * Read and filter cards from the registered JSON file.
     *
     * @param SearchCriteria $searchCriteria
     * @return array<string, mixed>
     * @throws ReflectionException
     */
    public function searchData(SearchCriteria $searchCriteria): array
    {
        $cards = $this->readFileToJson();
        $criteria = $searchCriteria->getFilterableValues();

        foreach ($searchCriteria->getFilters() as $filter) {
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
}
