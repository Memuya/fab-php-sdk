<?php

namespace Tests\Integration\Reader\Csv;

use PHPUnit\Framework\TestCase;
use Tests\Fixtures\TestSearchCriteria;
use Memuya\Fab\Readers\Csv\FileCsvReader;

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
        $cards = $this->reader->searchFile(
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
        $cards = $this->reader->searchFile(
            new TestSearchCriteria([
                'identifier' => 'first',
                'cost' => '2',
            ]),
        );

        $this->assertEmpty($cards);
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

    public function testCanChangeTheSeparatorNeededToReadTheCsv(): void
    {
        $reader = new FileCsvReader(
            filepath: sprintf('%s/test_cards_tabbed.csv', __DIR__),
            separator: "\t",
        );

        $cards = $reader->searchFile(
            new TestSearchCriteria([
                'identifier' => 'second',
            ]),
        );

        $this->assertNotEmpty($cards);
        $this->assertCount(1, $cards);
        $this->assertSame('second', $cards[0]['identifier']);
    }
}
