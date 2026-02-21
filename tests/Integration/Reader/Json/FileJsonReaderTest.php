<?php

namespace Integration\Reader\Json;

use PHPUnit\Framework\TestCase;
use Memuya\Fab\Attributes\Filter;
use Memuya\Fab\Readers\SearchCriteria;
use Memuya\Fab\Readers\Json\FileJsonReader;
use Memuya\Fab\Readers\Json\Filters\Filterable;

final class FileJsonReaderTest extends TestCase
{
    private FileJsonReader $reader;

    public function setUp(): void
    {
        $this->reader = new FileJsonReader(
            filepath: sprintf('%s/test_cards.json', __DIR__),
        );
    }

    public function testCanReadFromJsonFile(): void
    {
        $cards = $this->reader->searchData(new FileAdapterTestCardsSearchCriteria());

        $this->assertNotEmpty($cards);
    }

    public function testCanFilterResults(): void
    {
        $cards = $this->reader->searchData(
            new FileAdapterTestCardsSearchCriteria([
                'identifier' => 'first',
            ]),
        );

        $this->assertNotEmpty($cards);
        $this->assertCount(1, $cards);
        $this->assertSame('first', $cards[0]['identifier']);
    }

    public function testResultIsEmptyWhenFiltersDoNotMatchACard(): void
    {
        $cards = $this->reader->searchData(
            new FileAdapterTestCardsSearchCriteria([
                'identifier' => 'does_not_exist',
            ]),
        );

        $this->assertEmpty($cards);
    }
}

class FileAdapterTestCardsSearchCriteria extends SearchCriteria
{
    #[Filter(FileAdapterTestIdentifierFilter::class)]
    public string $identifier;
}

class FileAdapterTestCardSearchCriteria extends SearchCriteria
{
    #[Filter(FileAdapterTestIdentifierFilter::class)]
    public string $identifier;
}

class TestSearchCriteria extends SearchCriteria
{
    #[Filter(FileAdapterTestIdentifierFilter::class)]
    public string $identifier;

    #[Filter(FileAdapterTestCostFilter::class)]
    public string $cost;
}

class FileAdapterTestIdentifierFilter implements Filterable
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

class FileAdapterTestCostFilter implements Filterable
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
