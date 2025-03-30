<?php

use PHPUnit\Framework\TestCase;
use Memuya\Fab\Adapters\TheFabCube\SearchCriteria\Card\CardSearchCriteria;

final class CardSearchCriteriaTest extends TestCase
{
    public function testCanSetIdentifier()
    {
        $identifier = 'test';
        $config = new CardSearchCriteria(['name' => $identifier]);

        $this->assertSame($identifier, $config->name);
    }
}
