<?php

namespace Memuya\Fab\Downloader\Extractors;

use Closure;
use Memuya\Fab\Clients\TheFabCube\Entities\Card;
use Memuya\Fab\Clients\TheFabCube\Entities\Printing;

class TheFabCubeClientExtractor implements ImageUrlExtractor
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
