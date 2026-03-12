<?php

namespace Tests\Integration\Reader\Json;

use Tests\Fixtures\TestSearchCriteria;
use PHPUnit\Framework\TestCase;
use Memuya\Fab\Readers\Json\FileJsonReader;

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
