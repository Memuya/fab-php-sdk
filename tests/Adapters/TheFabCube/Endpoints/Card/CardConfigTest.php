<?php

use PHPUnit\Framework\TestCase;
use Memuya\Fab\Adapters\TheFabCube\Endpoints\Card\CardConfig;

final class CardConfigTest extends TestCase
{
    public function testCanSetIdentifier()
    {
        $identifier = 'test';
        $config = new CardConfig(['name' => $identifier]);

        $this->assertSame($identifier, $config->name);
    }
}
