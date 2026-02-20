<?php

namespace Integration\Reader\Json;

use PHPUnit\Framework\TestCase;
use Memuya\Fab\Attributes\Parameter;
use Memuya\Fab\Adapters\SearchCriteria;
use Memuya\Fab\Readers\Json\FileJsonReader;
use Memuya\Fab\Readers\Json\Filters\Filterable;

final class FileJsonReaderTest extends TestCase
{
    private FileJsonReader $reader;

    public function setUp(): void
    {
        $this->reader = new FileJsonReader(
            filepath: sprintf('%s/test_cards.json', __DIR__),
            filters: [FileAdapterTestIdentifierFilter::class],
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

    public function testCanSearchFileWithCustomFilterAndConfigViaConstructor(): void
    {
        $this->reader->registerFilters([FileAdapterTestCostFilter::class]);

        $cards = $this->reader->searchData(
            new TestSearchCriteria(['cost' => '1']),
        );

        $this->assertIsArray($cards);
        $this->assertCount(1, $cards);
    }

    public function testCanRegisterConfigDirectlyToSearchDataWithCustomFilter(): void
    {
        $this->reader->registerFilters([FileAdapterTestCostFilter::class]);

        $cards = $this->reader->searchData(
            new TestSearchCriteria(['cost' => '2']),
        );

        $this->assertIsArray($cards);
        $this->assertCount(1, $cards);
    }
}

class FileAdapterTestCardsSearchCriteria extends SearchCriteria
{
    #[Parameter]
    public string $identifier;
}

class FileAdapterTestCardSearchCriteria extends SearchCriteria
{
    #[Parameter]
    public string $identifier;
}

class TestSearchCriteria extends SearchCriteria
{
    #[Parameter]
    public string $identifier;

    #[Parameter]
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
    public function applyTo(array $data, array $filters): array
    {
        return array_filter($data, function ($card) use ($filters) {
            return str_contains($card['identifier'], $filters['identifier']);
        });
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
    public function applyTo(array $data, array $filters): array
    {
        return array_filter($data, function ($card) use ($filters) {
            return str_contains($card['cost'], $filters['cost']);
        });
    }
}
