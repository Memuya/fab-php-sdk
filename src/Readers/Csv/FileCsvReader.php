<?php

namespace Memuya\Fab\Readers\Csv;

use RuntimeException;
use Memuya\Fab\Readers\Reader;
use Memuya\Fab\Readers\SearchCriteria;
use Memuya\Fab\Readers\Json\Filters\Filterable;

readonly class FileCsvReader implements Reader
{
    public function __construct(
        private string $filepath,
        private string $separator = ",",
    ) {}

    /**
     * @inheritDoc
     */
    public function searchData(SearchCriteria $searchCriteria): array
    {
        /** @var list<Filterable> $filters */
        $filters = [];
        /** @var list<array<string, string>> $results */
        $results = [];
        $stream = $this->openFile();
        $headers = fgetcsv($stream, separator: $this->separator);
        $criteria = $searchCriteria->getFilterableValues();

        while (($row = fgetcsv($stream, separator: $this->separator)) !== false) {
            $allFiltersMatchRow = true;
            /** @var array<string, string> $data */
            $data = array_combine($headers, $row);

            foreach ($searchCriteria->getFilters() as $filter) {
                if (! $filter->canResolve($criteria)) {
                    continue;
                }

                /** @var list<Filterable> $filters */
                $filters[] = $filter;
            }

            foreach ($filters as $filter) {
                // If a filter doesn't match the row data, we flag it and stop applying any other filters.
                if (! $filter->applyTo($data, $criteria)) {
                    $allFiltersMatchRow = false;
                    break;
                }
            }

            // All filters couldn't be matched so don't add to $results and go to the next row.
            if (! $allFiltersMatchRow) {
                continue;
            }

            $results[] = $data;
        }

        fclose($stream);

        return $results;
    }

    /**
     * Open the CSV file.
     *
     * @return resource|false
     * @throws RuntimeException
     */
    private function openFile()
    {
        if (($stream = fopen($this->filepath, 'r')) === false) {
            throw new RuntimeException('Unable to open file.');
        }

        return $stream;
    }
}
