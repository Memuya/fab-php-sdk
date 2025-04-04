<?php

namespace Memuya\Fab\Utilities\Extract\Extractor;

interface Extractor
{
    /**
     * Extract the value.
     *
     * @return mixed
     */
    public function extract(): mixed;
}
