<?php

use PHPUnit\Framework\TestCase;
use Memuya\Fab\Adapters\File\FileAdapter;

final class FileAdapterTest extends TestCase
{
    private string $testCardsJsonFilePath;
    private FileAdapter $adapter;

    public function setUp(): void
    {
        $this->testCardsJsonFilePath = sprintf('%s/test_cards.json', dirname(__DIR__, 3));

        $this->adapter = new FileAdapter($this->testCardsJsonFilePath, [new FileAdapterTestIdentifierFilter()]);
    }

    public function testCanReadFromJsonFile(): void
    {
        $cards = $this->adapter->getCards(new FileAdapterTestCardsSearchCriteria());

        $this->assertNotEmpty($cards);
    }

    public function testCanFilterResults(): void
    {
        $cards = $this->adapter->getCards(
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
        $cards = $this->adapter->getCards(
            new FileAdapterTestCardsSearchCriteria([
                'identifier' => 'does_not_exist',
            ]),
        );

        $this->assertEmpty($cards);
    }

    public function testCanFilterFileWithCustomFilterAndConfigViaConstructor(): void
    {
        $this->adapter->registerFilters([new FileAdapterTestCostFilter()]);

        $cards = $this->adapter->filterList(
            new TestSearchCriteria(['cost' => '1']),
        );

        $this->assertIsArray($cards);
        $this->assertCount(1, $cards);
    }

    public function testCanRegisterConfigDirectlyToFilterListWithCustomFilter(): void
    {
        $this->adapter->registerFilters([new FileAdapterTestCostFilter()]);

        $cards = $this->adapter->filterList(
            new TestSearchCriteria(['cost' => '2']),
        );

        $this->assertIsArray($cards);
        $this->assertCount(1, $cards);
    }
}

class FileAdapterTestCardsSearchCriteria extends \Memuya\Fab\Adapters\SearchCriteria
{
    #[\Memuya\Fab\Attributes\Parameter]
    public string $identifier;
}

class FileAdapterTestCardSearchCriteria extends \Memuya\Fab\Adapters\SearchCriteria
{
    #[\Memuya\Fab\Attributes\Parameter]
    public string $identifier;
}

class TestSearchCriteria extends \Memuya\Fab\Adapters\SearchCriteria
{
    #[\Memuya\Fab\Attributes\Parameter]
    public string $identifier;

    #[\Memuya\Fab\Attributes\Parameter]
    public string $cost;
}

class FileAdapterTestIdentifierFilter implements \Memuya\Fab\Adapters\File\Filters\Filterable
{
    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['identifier']) && ! is_null($filters['identifier']);
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

class FileAdapterTestCostFilter implements \Memuya\Fab\Adapters\File\Filters\Filterable
{
    /**
     * @inheritDoc
     */
    public function canResolve(array $filters): bool
    {
        return isset($filters['cost']) && ! is_null($filters['cost']);
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
