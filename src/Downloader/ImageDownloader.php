<?php

namespace Memuya\Fab\Downloader;

use Memuya\Fab\Enums\Set;
use Memuya\Fab\Clients\Client;
use Memuya\Fab\Downloader\Extractors\ImageUrlExtractor;

class ImageDownloader
{
    /**
     * The client.
     *
     * @var Client
     */
    private Client $client;

    /**
     * The directory path where the images will be stored.
     *
     * @var string
     */
    private string $uploadDirectory;

    /**
     * The extractor used to extract the image URLs. This should line up
     * with the data returned from the given Client.
     *
     * @var ImageUrlExtractor
     */
    private ImageUrlExtractor $extractor;

    /**
     * @param Client $client
     * @param string $uploadDirectory
     * @param ImageUrlExtractor $extractor
     */
    public function __construct(Client $client, string $uploadDirectory, ImageUrlExtractor $extractor)
    {
        $this->client = $client;
        $this->uploadDirectory = sprintf('%s/', rtrim($uploadDirectory, '/'));
        $this->extractor = $extractor;
    }

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
     * Download all images into a sub-folder for the given set.
     *
     * @param Set $set
     * @return void
     */
    public function forSet(Set $set): void
    {
        $originalUploadDirectory = $this->uploadDirectory;
        $this->uploadDirectory = $this->uploadDirectory . $set->value . '/';

        try {
            $this->filterBy(['set' => $set]);
        } finally {
            $this->uploadDirectory = $originalUploadDirectory;
        }

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
        $cards = $this->client->getCards($filters);

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
        if (! is_dir($this->uploadDirectory)) {
            mkdir(directory: $this->uploadDirectory, recursive: true);
        }

        foreach ($urls as $url) {
            file_put_contents(
                $this->generateFullFilePathFromUrl($url),
                file_get_contents($url),
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

    public function setImageUrlExtractor(ImageUrlExtractor $extractor)
    {
        $this->extractor = $extractor;
    }

    /**
     * Generate the full upload path and filename from the given URL.
     *
     * @param string $url
     * @return string
     */
    private function generateFullFilePathFromUrl(string $url): string
    {
        return sprintf(
            '%s%s',
            $this->uploadDirectory,
            $this->getImageNameFromUrl($url),
        );
    }
}
