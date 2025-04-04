<?php

namespace Memuya\Fab\Downloader;

use Memuya\Fab\Adapters\Adapter;
use Memuya\Fab\Adapters\SearchCriteria;
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
     * Download a list of images based on the given filters.
     *
     * @param array $filters
     * @return void
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
     * @return array<string>
     */
    public function getImageUrls(SearchCriteria $searchCriteria): array
    {
        $imageUrls = [];
        $cards = $this->adapter->getCards($searchCriteria);

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
                $this->getImageContentFromUrl($url),
            );
        }
    }

    /**
     * Download the image content from the given URL.
     *
     * @param string $url
     * @return string|false
     */
    public function getImageContentFromUrl(string $url): string|false
    {
        return file_get_contents($url);
    }

    /**
     * Get the name of the file from the URL.
     *
     * @param string $url
     * @return string
     */
    private function getImageNameFromUrl(string $url): string
    {
        return basename($url);
    }
}
