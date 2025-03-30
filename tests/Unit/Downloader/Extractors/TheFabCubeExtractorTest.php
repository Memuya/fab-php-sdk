<?php

use PHPUnit\Framework\TestCase;
use Memuya\Fab\Adapters\TheFabCube\Entities\Card;
use Memuya\Fab\Adapters\TheFabCube\Entities\Printing;
use Memuya\Fab\Downloader\Extractors\TheFabCubeExtractor;

class TheFabCubeExtractorTest extends TestCase
{
    public function testCanExtractImageUrls(): void
    {
        $printing1 = $this->createMock(Printing::class);
        $printing1->imageUrl = 'https://example.com/image1.jpg';

        $printing2 = $this->createMock(Printing::class);
        $printing2->imageUrl = 'https://example.com/image2.jpg';

        $card = $this->createMock(Card::class);
        $card->printings = [$printing1, $printing2];

        $extractor = new TheFabCubeExtractor();

        $extractorClosure = $extractor();
        $imageUrls = $extractorClosure($card);

        $expectedUrls = [
            'https://example.com/image1.jpg',
            'https://example.com/image2.jpg',
        ];

        $this->assertSame($expectedUrls, $imageUrls);
    }
}
