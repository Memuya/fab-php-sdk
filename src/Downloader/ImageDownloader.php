<?php

namespace Memuya\Fab\Downloader;

use Memuya\Fab\Enums\Set;
use Memuya\Fab\Adapters\Adapter;
use League\Flysystem\FilesystemOperator;
use Memuya\Fab\Downloader\Extractors\ImageUrlExtractor;

class ImageDownloader
{
    public function __construct(
        private Adapter $adapter,
        private ImageUrlExtractor $extractor,
        private FilesystemOperator $filesystem,
    ) {}

    /**
     * Download all images.
     *
     * @return void
     */
    public function downloadAll(): void
    {
        $this->filterBy();
    }

    /**
     * Download all images for the given set.
     *
     * @param Set $set
     * @return void
     */
    public function forSet(Set $set): void
    {
        $this->filterBy(['set' => $set]);
    }

    /**
     * Download a list of images based on the given filters.
     *
     * @param array $filters
     * @return void
     */
    public function filterBy(array $filters = []): void
    {
        $this->downloadFromUrls(
            $this->getImageUrls($filters),
        );
    }

    /**
     * Return all the image URLs based on the given filters.
     *
     * @param array<string, mixed> $filters
     * @return array<string>
     */
    public function getImageUrls(array $filters = []): array
    {
        $imageUrls = [];
        $cards = $this->adapter->getCards($filters);

        foreach ($cards as $card) {
            $printings = ($this->extractor)()($card);

            $imageUrls = array_merge(
                $imageUrls,
                array_filter($printings),
            );
        }

        return $imageUrls;
    }

    /**
     * Download the images from the given URLs.
     *
     * @param array<string> $urls
     * @return void
     */
    public function downloadFromUrls(array $urls): void
    {
        foreach ($urls as $url) {
            $this->filesystem->write(
                $this->getImageNameFromUrl($url),
                file_get_contents($url)
            );
        }
    }

    /**
     * Get the name of the file from the URL.
     *
     * @param string $url
     * @return string
     */
    public function getImageNameFromUrl(string $url): string
    {
        return basename($url);
    }
}
