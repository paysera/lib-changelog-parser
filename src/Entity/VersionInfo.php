<?php

namespace Paysera\Component\ChangelogParser\Entity;

use DateTimeInterface;

class VersionInfo
{
    const VERSION_UNRELEASED = 'Unreleased';

    private $version;
    private $date;
    private $changeEntries;

    public function __construct()
    {
        $this->changeEntries = [];
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param DateTimeInterface|null $date
     *
     * @return $this
     */
    public function setDate($date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return ChangeEntry[]
     */
    public function getChangeEntries(): array
    {
        return $this->changeEntries;
    }

    /**
     * @param ChangeEntry[] $changeEntries
     *
     * @return $this
     */
    public function setChangeEntries(array $changeEntries): self
    {
        $this->changeEntries = $changeEntries;
        return $this;
    }

    /**
     * @param ChangeEntry $changeEntry
     * @return $this
     */
    public function addChangeEntry(ChangeEntry $changeEntry): self
    {
        $this->changeEntries[] = $changeEntry;
        return $this;
    }
}
