<?php

use Memuya\Fab\Clients\Client;
use PHPUnit\Framework\TestCase;
use Memuya\Fab\Downloader\ImageDownloader;
use PHPUnit\Framework\MockObject\MockObject;
use Memuya\Fab\Clients\TheFabCube\Entities\Printing;
use Memuya\Fab\Downloader\Extractors\ImageUrlExtractor;

class ImageDownloaderTest extends TestCase
{
    private MockObject&Client $clientMock;
    private MockObject&ImageUrlExtractor $extractorMock;
    private ImageDownloader $imageDownloader;
    private string $uploadDirectory;

    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(Client::class);
        $this->extractorMock = $this->createMock(ImageUrlExtractor::class);
        $this->uploadDirectory = sys_get_temp_dir() . '/images/';
        $this->imageDownloader = new ImageDownloader(
            $this->clientMock,
            $this->extractorMock,
            $this->uploadDirectory,
        );
    }

    public function testGetImageUrls(): void
    {
        $printing = $this->createMock(Printing::class);
        $printing->imageUrl = 'https://example.com/image1.jpg';

        $card = new class {
            public array $printings;
        };
        $card->printings = [$printing];

        $this->extractorMock
            ->method('__invoke')
            ->willReturn(fn($card) => [$printing->imageUrl]);

        $this->clientMock
            ->expects($this->once())
            ->method('getCards')
            ->with([])
            ->willReturn([$card]);

        $urls = $this->imageDownloader->getImageUrls();

        $this->assertCount(1, $urls);
        $this->assertSame('https://example.com/image1.jpg', $urls[0]);
    }

    public function testGetImageNameFromUrl(): void
    {
        $url = 'https://example.com/path/to/image.jpg';
        $imageName = $this->imageDownloader->getImageNameFromUrl($url);

        $this->assertSame('image.jpg', $imageName);
    }

    public function testGenerateFullFilePathFromUrl(): void
    {
        $url = 'https://example.com/image.jpg';
        $reflection = new \ReflectionClass(ImageDownloader::class);
        $method = $reflection->getMethod('generateFullFilePathFromUrl');
        $method->setAccessible(true);

        $expectedPath = $this->uploadDirectory . 'image.jpg';
        $this->assertSame($expectedPath, $method->invoke($this->imageDownloader, $url));
    }
}
