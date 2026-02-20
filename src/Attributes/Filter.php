<?php

namespace Memuya\Fab\Attributes;

use Attribute;
use Memuya\Fab\Readers\Json\Filters\Filterable;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Filter
{
    /**
     * @param class-string<Filterable> $filterClass
     */
    public function __construct(public string $filterClass) {}
}
