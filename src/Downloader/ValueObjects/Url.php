<?php

namespace Memuya\Fab\Downloader\ValueObjects;

use InvalidArgumentException;

final readonly class Url
{
    public string $value;

    public function __construct(string $value)
    {
        if (! filter_var($value, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Not a valid URL.');
        }

        $this->value = $value;
    }
}
