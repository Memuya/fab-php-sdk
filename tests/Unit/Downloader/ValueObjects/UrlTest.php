<?php

namespace Tests\Unit\Downloader\ValueObjects;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Memuya\Fab\Downloader\ValueObjects\Url;

class UrlTest extends TestCase
{
    public function testSetsSuccessfullyIfValueIsAValidUrl(): void
    {
        $url = new Url('https://test.com');

        $this->assertSame('https://test.com', $url->value);
    }

    public function testThrowsExceptionWhenValueIsNotAValidUrl(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Not a valid URL.');

        new Url('invalid');
    }
}