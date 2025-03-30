<?php

namespace Memuya\Fab\Downloader\Extractors;

use Closure;
use Memuya\Fab\Adapters\TheFabCube\Entities\Card;
use Memuya\Fab\Adapters\TheFabCube\Entities\Printing;

class TheFabCubeExtractor implements ImageUrlExtractor
{
    public function __invoke(): Closure
    {
        /** @return array<string> */
        return function (Card $card): array {
            return array_map(
                fn(Printing $printing) => $printing->imageUrl,
                $card->printings,
            );
        };
    }
}
