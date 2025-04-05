<?php

use Memuya\Fab\Enums\Pitch;
use PHPUnit\Framework\TestCase;
use Memuya\Fab\Utilities\CompareWithOperator;
use Memuya\Fab\Adapters\TheFabCube\Entities\Card;
use Memuya\Fab\Adapters\TheFabCube\TheFabCubeAdapter;
use Memuya\Fab\Adapters\TheFabCube\SearchCriteria\Cards\CardsSearchCriteria;

final class TheFabCubeAdapterTest extends TestCase
{
    private string $cardsJsonFilePath;
    private TheFabCubeAdapter $adapter;

    public function setUp(): void
    {
        $this->cardsJsonFilePath = sprintf('%s/the_fab_cube_cards.json', dirname(__DIR__, 3));
        $this->adapter = new TheFabCubeAdapter($this->cardsJsonFilePath);
    }

    public function testCanReadFromJsonFile(): void
    {
        $cards = $this->adapter->getCards(new CardsSearchCriteria());

        $this->assertNotEmpty($cards);
    }

    public function testCanFilterResults(): void
    {
        $cards = $this->adapter->getCards(
            new CardsSearchCriteria([
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
            new CardsSearchCriteria([
                'name' => '10,000 Year Reunion',
                'pitch' => new CompareWithOperator(Pitch::Two),
            ]),
        );

        $this->assertEmpty($cards);
    }
}
