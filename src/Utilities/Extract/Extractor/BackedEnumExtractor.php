<?php

namespace Memuya\Fab\Utilities\Extract\Extractor;

use BackedEnum;
use Memuya\Fab\Utilities\Extract\Extractor\Extractor;

class BackedEnumExtractor implements Extractor
{
    private BackedEnum $enum;

    public function __construct(BackedEnum $enum)
    {
        $this->enum = $enum;
    }

    public function extract(): mixed
    {
        return $this->enum->value;
    }
}
