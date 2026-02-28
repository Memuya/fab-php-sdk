<?php

namespace Memuya\Fab\Readers\Json;

use RuntimeException;
use Memuya\Fab\Readers\Reader;
use Memuya\Fab\Readers\SearchCriteria;
use Memuya\Fab\Readers\Json\Filters\Filterable;

class FileJsonReader implements Reader
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
     * @inheritDoc
     */
    public function searchData(SearchCriteria $searchCriteria): array
    {
        $fileData = $this->readFileToJson();
        $criteria = $searchCriteria->getFilterableValues();
        $filters = [];

        foreach ($searchCriteria->getFilters() as $filter) {
            // If we can't resolve the filter, just go to the next one.
            if (! $filter->canResolve($criteria)) {
                continue;
            }

            /** @var array<Filterable> $filters */
            $filters[] = $filter;
        }

        // If no filter could resolve, return everything.
        if (empty($filters)) {
            return $fileData;
        }

        return array_values(
            $this->applyFilters($fileData, $filters, $criteria),
        );
    }

    /**
     * Apply the resolved filters to the file data.
     *
     * @param array<string, mixed> $fileData
     * @param array<Filterable> $filters
     * @param array<string, mixed> $criteria
     * @return array<string, mixed>
     */
    private function applyFilters(array $fileData, array $filters, array $criteria): array
    {
        return array_filter($fileData, function (array $data) use ($filters, $criteria): bool {
            foreach ($filters as $filter) {
                // If the filter can't apply it's check to the current line of data from the file
                // we don't want it returned in the final result.
                if (! $filter->applyTo($data, $criteria)) {
                    return false;
                }
            }

            // The filter successfully applied so we return it in the final result.
            return true;
        });
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
