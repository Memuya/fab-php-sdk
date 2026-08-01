<?php

namespace Memuya\Fab\Downloader;

use Memuya\Fab\Adapters\Adapter;
use Memuya\Fab\Readers\SearchCriteria;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\FilesystemException;
use Memuya\Fab\Downloader\ValueObjects\Url;
use Memuya\Fab\Downloader\Extractors\ImageUrlExtractor;

class ImageDownloader
{
    public function __construct(
        private readonly Adapter $adapter,
        private readonly ImageUrlExtractor $extractor,
        private readonly FilesystemOperator $filesystem,
    ) {}

    /**
     * Download a list of images based on the given filters.
     *
     * @param SearchCriteria $searchCriteria
     * @return void
     * @throws FilesystemException
     */
    public function filterBy(SearchCriteria $searchCriteria): void
    {
        $this->downloadFromUrls(
            $this->getImageUrls($searchCriteria),
        );
    }

    /**
     * Return all the image URLs based on the given filters.
     *
     * @param SearchCriteria $searchCriteria
     * @return list<Url>
     */
    public function getImageUrls(SearchCriteria $searchCriteria): array
    {
        $imageUrls = [];
        $cards = $this->adapter->getCards($searchCriteria);

        foreach ($cards as $card) {
            $printings = ($this->extractor)()($card);

            $imageUrls = [
                ...$imageUrls,
                ...array_map(fn(string $url): Url => new Url($url), $printings),
            ];
        }

        return $imageUrls;
    }

    /**
     * Download the images from the given URLs.
     *
     * @param list<Url> $urls
     * @return void
     * @throws FilesystemException
     */
    public function downloadFromUrls(array $urls): void
    {
        foreach ($urls as $url) {
            $this->filesystem->write(
                $this->getImageNameFromUrl($url),
                $this->getImageContentFromUrl($url),
            );
        }
    }

    /**
     * Download the image content from the given URL.
     *
     * @param Url $url
     * @return string|false
     */
    public function getImageContentFromUrl(Url $url): string|false
    {
        return file_get_contents($url->value);
    }

    /**
     * Get the name of the file from the URL.
     *
     * @param Url $url
     * @return string
     */
    private function getImageNameFromUrl(Url $url): string
    {
        return basename($url->value);
    }
}
