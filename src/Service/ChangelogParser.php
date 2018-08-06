<?php
declare(strict_types=1);

namespace Paysera\Component\ChangelogParser\Service;

use Iterator;
use Paysera\Component\ChangelogParser\Entity\ChangeDetails;
use Paysera\Component\ChangelogParser\Entity\ChangeEntry;
use Paysera\Component\ChangelogParser\Entity\Changelog;
use Paysera\Component\ChangelogParser\Entity\VersionInfo;
use Paysera\Component\ChangelogParser\Exception\ChangelogException;

class ChangelogParser
{
    private $extractor;

    public function __construct(ValueExtractor $extractor)
    {
        $this->extractor = $extractor;
    }

    public function parse(string $contents): Changelog
    {
        $trimmed = trim($contents);
        $changelog = new Changelog();

        $changelog
            ->setHeader(trim($this->extractor->extractHeader($trimmed)))
        ;

        $partial = substr($contents, strlen($changelog->getHeader()));

        foreach ($this->parseVersions($partial) as $versionInfo) {
            $changelog->addVersion($versionInfo);
        }

        return $changelog;
    }

    /**
     * @param string $contents
     * @return Iterator|VersionInfo[]
     */
    private function parseVersions(string $contents): Iterator
    {
        foreach ($this->extractor->extractVersionBlocks($contents) as $block) {
            yield $this->parseVersionInfo($block);
        }
    }

    public function parseVersionInfo(string $block): VersionInfo
    {
        $versionInfo = new VersionInfo();
        $version = $this->extractor->extractVersion($block);
        if ($version === null) {
            throw new ChangelogException('Cannot extract version number from block: ' . $block);
        }
        $versionInfo->setVersion($version);
        $versionInfo->setDate($this->extractor->extractVersionDate($block));

        foreach ($this->extractor->extractChangeEntryBlocks($block) as $changeEntryBlock) {
            $versionInfo->addChangeEntry($this->parseChangeEntry($changeEntryBlock));
        }

        return $versionInfo;
    }

    public function parseChangeEntry(string $block): ChangeEntry
    {
        $entry = new ChangeEntry();
        $changeType = $this->extractor->extractChangeType($block);
        if ($changeType === null) {
            throw new ChangelogException('Cannot extract change type from block: ' . $block);
        }

        $entry->setChangeType($changeType);

        foreach ($this->extractor->extractChangeDetailsBlocks($block) as $changeDetail) {
            $entry->addChangeDetails($this->parseChangeDetails($changeDetail));
        }

        return $entry;
    }

    private function parseChangeDetails(string $block): ChangeDetails
    {
        $details = new ChangeDetails();
        $description = $this->extractor->extractDetailsDescription($block);
        if ($description === null) {
            throw new ChangelogException('Cannot extract change description from block: ' . $block);
        }

        $details->setDescription($description);

        return $details;
    }
}
