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
        $cards = $this->reader->searchFile(new TestSearchCriteria());

        $this->assertNotEmpty($cards);
    }

    public function testCanFilterResults(): void
    {
        $cards = $this->reader->searchFile(
            new TestSearchCriteria([
                'identifier' => 'first',
            ]),
        );

        $this->assertNotEmpty($cards);
        $this->assertCount(1, $cards);
        $this->assertSame('first', $cards[0]['identifier']);
    }

    public function testResultIsEmptyWhenFiltersDoNotMatchACard(): void
    {
        $cards = $this->reader->searchFile(
            new TestSearchCriteria([
                'identifier' => 'does_not_exist',
            ]),
        );

        $this->assertEmpty($cards);
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
