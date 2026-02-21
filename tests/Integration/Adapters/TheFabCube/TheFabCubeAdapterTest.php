<?php

namespace Integration\Adapters\TheFabCube;

use Memuya\Fab\Enums\Pitch;
use PHPUnit\Framework\TestCase;
use Memuya\Fab\Readers\Json\FileJsonReader;
use Memuya\Fab\Utilities\CompareWithOperator;
use Memuya\Fab\Adapters\TheFabCube\Entities\Card;
use Memuya\Fab\Adapters\TheFabCube\TheFabCubeAdapter;
use Memuya\Fab\Adapters\TheFabCube\SearchCriteria\Cards\TheFabCubeSearchCriteria;

final class TheFabCubeAdapterTest extends TestCase
{
    private TheFabCubeAdapter $adapter;

    public function setUp(): void
    {
        $cardsJsonFilePath = sprintf('%s/example_cards.json', dirname(__DIR__, 3));

        $this->adapter = new TheFabCubeAdapter(new FileJsonReader($cardsJsonFilePath));
    }

    public function testCanReadFromJsonFile(): void
    {
        $cards = $this->adapter->getCards(new TheFabCubeSearchCriteria());

        $this->assertNotEmpty($cards);
    }

    public function testCanFilterResults(): void
    {
        $cards = $this->adapter->getCards(
            new TheFabCubeSearchCriteria([
                'name' => '10,000 Year Reunion',
                'pitch' => new CompareWithOperator(Pitch::One),
            ]),
        );

        $this->assertNotEmpty($cards);
        $this->assertCount(1, $cards);
        $this->assertContainsOnlyInstancesOf(Card::class, $cards);
        $this->assertSame('10,000 Year Reunion', $cards[0]->name);
    }

    public function testResultIsEmptyWhenFiltersDoNotMatchACard(): void
    {
        $cards = $this->adapter->getCards(
            new TheFabCubeSearchCriteria([
                'name' => '10,000 Year Reunion',
                'pitch' => new CompareWithOperator(Pitch::Two),
            ]),
        );

        $this->assertEmpty($cards);
    }

    public function testCanReturnUnderlyingFileJsonReader(): void
    {
        $this->assertInstanceOf(
            FileJsonReader::class,
            $this->adapter->getFileReader(),
        );
    }
}
