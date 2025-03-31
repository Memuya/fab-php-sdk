<?php

use PHPUnit\Framework\TestCase;
use League\Flysystem\Filesystem;
use Memuya\Fab\Adapters\Adapter;
use Memuya\Fab\Downloader\ImageDownloader;
use PHPUnit\Framework\MockObject\MockObject;
use Memuya\Fab\Adapters\TheFabCube\Entities\Printing;
use Memuya\Fab\Downloader\Extractors\ImageUrlExtractor;
use League\Flysystem\InMemory\InMemoryFilesystemAdapter;

class ImageDownloaderTest extends TestCase
{
    private MockObject&Adapter $adapterMock;
    private MockObject&ImageUrlExtractor $extractorMock;
    private ImageDownloader $imageDownloader;

    protected function setUp(): void
    {
        $this->adapterMock = $this->createMock(Adapter::class);
        $this->extractorMock = $this->createMock(ImageUrlExtractor::class);

        $this->imageDownloader = new ImageDownloader(
            $this->adapterMock,
            $this->extractorMock,
            new Filesystem(new InMemoryFilesystemAdapter()),
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

        $this->adapterMock
            ->expects($this->once())
            ->method('getCards')
            ->with([])
            ->willReturn([$card]);

        $urls = $this->imageDownloader->getImageUrls();

        $this->assertCount(1, $urls);
        $this->assertSame('https://example.com/image1.jpg', $urls[0]);
    }

    public function testDownloadFromUrls(): void
    {
        /** @var MockObject&Adapter */
        $adapter = $this->createMock(Adapter::class);
        /** @var MockObject&ImageUrlExtractor */
        $extractor = $this->createMock(ImageUrlExtractor::class);
        $filesystem = new Filesystem(new InMemoryFilesystemAdapter());

        /** @var MockObject&ImageDownloader */
        $downloader = $this->getMockBuilder(ImageDownloader::class)
            ->onlyMethods(['getImageContentForUrl'])
            ->setConstructorArgs([$adapter, $extractor, $filesystem])
            ->getMock();

        $downloader->method('getImageContentForUrl')
            ->willReturn('fake_image_content');

        $urls = [
            'https://example.com/image1.jpg',
            'https://example.com/image2.png',
        ];

        $downloader->downloadFromUrls($urls);

        $this->assertTrue($filesystem->fileExists('image1.jpg'));
        $this->assertTrue($filesystem->fileExists('image2.png'));
    }

}
