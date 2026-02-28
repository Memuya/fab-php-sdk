<?php

namespace Integration\Reader\Csv;

use Memuya\Fab\Readers\Csv\FileCsvReader;
use PHPUnit\Framework\TestCase;
use Memuya\Fab\Attributes\Filter;
use Memuya\Fab\Readers\SearchCriteria;
use Memuya\Fab\Readers\Json\Filters\Filterable;

final class FileCsvReaderTest extends TestCase
{
    private FileCsvReader $reader;

    public function setUp(): void
    {
        $this->reader = new FileCsvReader(
            filepath: sprintf('%s/test_cards.csv', __DIR__),
        );
    }

    public function testCanFilterResults(): void
    {
        $cards = $this->reader->searchData(
            new TestSearchCriteria([
                'identifier' => 'first',
                'cost' => '1',
            ]),
        );

        $this->assertNotEmpty($cards);
        $this->assertCount(1, $cards);
        $this->assertSame('first', $cards[0]['identifier']);
        $this->assertSame('1', $cards[0]['cost']);
    }

    public function testDoesNotReturnResultsIfAllFiltersDoNotPass(): void
    {
        $cards = $this->reader->searchData(
            new TestSearchCriteria([
                'identifier' => 'first',
                'cost' => '2',
            ]),
        );

        $this->assertEmpty($cards);
    }

    public function testResultIsEmptyWhenFiltersDoNotMatchACard(): void
    {
        $cards = $this->reader->searchData(
            new TestSearchCriteria([
                'identifier' => 'does_not_exist',
            ]),
        );

        $this->assertEmpty($cards);
    }

    public function testCanChangeTheSeparatorNeededToReadTheCsv(): void
    {
        $reader = new FileCsvReader(
            filepath: sprintf('%s/test_cards_tabbed.csv', __DIR__),
            separator: "\t",
        );

        $cards = $reader->searchData(
            new TestSearchCriteria([
                'identifier' => 'second',
            ]),
        );

        $this->assertNotEmpty($cards);
        $this->assertCount(1, $cards);
        $this->assertSame('second', $cards[0]['identifier']);
    }
}

class TestSearchCriteria extends SearchCriteria
{
    #[Filter(IdentifierFilter::class)]
    public string $identifier;

    #[Filter(CostFilter::class)]
    public string $cost;
}

class IdentifierFilter implements Filterable
{
    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['identifier']);
    }

    /**
     * @inheritDoc
     */
    public function applyTo(array $item, array $filters): bool
    {
        return str_contains($item['identifier'], $filters['identifier']);
    }
}

class CostFilter implements Filterable
{
    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['cost']);
    }

    /**
     * @inheritDoc
     */
    public function applyTo(array $item, array $filters): bool
    {
        return str_contains($item['cost'], $filters['cost']);
    }
}
