<?php

namespace Memuya\Fab\Utilities\Sync;

use League\Flysystem\Filesystem;
use Memuya\Fab\Adapters\Adapter;
use Memuya\Fab\Adapters\Entity;
use Memuya\Fab\Readers\SearchCriteria;
use RuntimeException;

final readonly class ExternalCardSync
{
    public function __construct(private Adapter $adapter, private Filesystem $filesystem) {}

    /**
     * Download from the source and process each items based on the criteria filtering.
     *
     * @param string $from
     * @param string $to
     * @param SearchCriteria $criteria
     * @param callable(array|Entity) $fn
     * @return void
     */
    public function downloadAndProcess(string $from, string $to, SearchCriteria $criteria, callable $fn): void
    {
        $this->download($from, $to)->process($criteria, $fn);
    }

    /**
     * Write the raw contents of the file containing the card information.
     *
     * @param string $from  The source URL
     * @param string $to  The file to write the source to
     * @return self
     */
    public function download(string $from, string $to): self
    {
        $this->filesystem->write($to, $this->fetchSource($from));

        return $this;
    }

    public function fetchSource(string $from): string
    {
        $contents = file_get_contents($from);

        if ($contents === false) {
            throw new RuntimeException("Could not fetch from source: $from");
        }

        return $contents;
    }

    /**
     * Process each card from the downloaded source.
     *
     * @param SearchCriteria $criteria
     * @param callable(array|Entity) $fn
     * @return void
     */
    public function process(SearchCriteria $criteria, callable $fn): void
    {
        $cards = $this->adapter->getCards($criteria);

        foreach ($cards as $card) {
            $fn($card);
        }
    }
}
