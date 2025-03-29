<?php

namespace Memuya\Fab\Downloader\Extractors;

use Closure;

interface ImageUrlExtractor
{
    public function __invoke(): Closure;
}
