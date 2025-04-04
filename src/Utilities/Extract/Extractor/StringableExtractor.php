<?php

namespace Memuya\Fab\Utilities\Extract\Extractor;

use Stringable;
use Memuya\Fab\Utilities\Extract\Extractor\Extractor;

class StringableExtractor implements Extractor
{
    private Stringable $stringable;

    public function __construct(Stringable $stringable)
    {
        $this->stringable = $stringable;
    }

    public function extract(): string
    {
        return (string) $this->stringable;
    }
}
