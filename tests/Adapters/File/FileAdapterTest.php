<?php

use PHPUnit\Framework\TestCase;
use Memuya\Fab\Adapters\File\ConfigType;
use Memuya\Fab\Adapters\File\FileAdapter;

final class FileAdapterTest extends TestCase
{
    private string $testCardsJsonFilePath;
    private FileAdapter $adapter;

    public function setUp(): void
    {
        $this->testCardsJsonFilePath = sprintf('%s/test_cards.json', dirname(__DIR__, 2));

        $this->adapter = new FileAdapter($this->testCardsJsonFilePath, [new FileAdapterTestIdentifierFilter()]);
        $this->adapter->registerConfig(ConfigType::MultiCard, FileAdapterTestCardsConfig::class);
        $this->adapter->registerConfig(ConfigType::SingleCard, FileAdapterTestCardConfig::class);
    }

    public function testCanReadFromJsonFile(): void
    {
        $cards = $this->adapter->getCards();

        $this->assertNotEmpty($cards);
    }

    public function testCanFilterResults(): void
    {
        $cards = $this->adapter->getCards([
            'identifier' => 'first',
        ]);

        $this->assertNotEmpty($cards);
        $this->assertCount(1, $cards);
        $this->assertSame('first', $cards[0]['identifier']);
    }

    public function testResultIsEmptyWhenFiltersDoNotMatchACard(): void
    {
        $cards = $this->adapter->getCards([
            'identifier' => 'does_not_exist',
        ]);

        $this->assertEmpty($cards);
    }

    public function testCanReturnASingleCard(): void
    {
        $card = $this->adapter->getCard('second', 'identifier');

        $this->assertIsArray($card);
        $this->assertSame('second', $card['identifier']);
    }

    public function testCanFilterFileWithCustomFilterAndConfigViaConstructor(): void
    {
        $this->adapter->registerFilters([new FileAdapterTestCostFilter()]);

        $cards = $this->adapter->filterList(
            new TestConfig(['cost' => '1']),
        );

        $this->assertIsArray($cards);
        $this->assertCount(1, $cards);
    }

    public function testCanRegisterConfigDirectlyToFilterListWithCustomFilter(): void
    {
        $this->adapter->registerFilters([new FileAdapterTestCostFilter()]);

        $cards = $this->adapter->filterList(
            new TestConfig(['cost' => '2']),
        );

        $this->assertIsArray($cards);
        $this->assertCount(1, $cards);
    }

    public function testCanRegisterDifferentConfigForCardsEndpoint(): void
    {
        $this->adapter->registerFilters([new FileAdapterTestCostFilter()]);
        $this->adapter->registerConfig(ConfigType::MultiCard, TestConfig::class);

        $cards = $this->adapter->getCards(['cost' => '1']);

        $this->assertIsArray($cards);
        $this->assertCount(1, $cards);
    }

    public function testCanRegisterDifferentConfigForCardEndpoint(): void
    {
        $this->adapter->registerFilters([new FileAdapterTestCostFilter()]);
        $this->adapter->registerConfig(ConfigType::SingleCard, TestConfig::class);

        $card = $this->adapter->getCard('1', 'cost');

        $this->assertIsArray($card);
        $this->assertSame('1', $card['cost']);
    }
}

class FileAdapterTestCardsConfig extends \Memuya\Fab\Adapters\Config
{
    #[\Memuya\Fab\Attributes\Parameter]
    public string $identifier;
}

class FileAdapterTestCardConfig extends \Memuya\Fab\Adapters\Config
{
    #[\Memuya\Fab\Attributes\Parameter]
    public string $identifier;
}

class TestConfig extends \Memuya\Fab\Adapters\Config
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
