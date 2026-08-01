<?php

namespace Memuya\Fab\Downloader\ValueObjects;

final readonly class Url
{
    public function __construct(public string $value) {}
}
