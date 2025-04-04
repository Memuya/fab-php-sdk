<?php

namespace Memuya\Fab\Utilities\Extract\Extractor;

use UnitEnum;
use Memuya\Fab\Utilities\Extract\Extractor\Extractor;

class UnitEnumExtractor implements Extractor
{
    private UnitEnum $enum;

    public function __construct(UnitEnum $enum)
    {
        $this->enum = $enum;
    }

    public function extract(): mixed
    {
        return $this->enum->name;
    }
}
