<?php
declare(strict_types=1);

namespace Paysera\Component\ChangelogParser\Entity;

class ChangeEntry
{
    const CHANGE_TYPE_ADDED = 'Added';
    const CHANGE_TYPE_CHANGED = 'Changed';
    const CHANGE_TYPE_DEPRECATED = 'Deprecated';
    const CHANGE_TYPE_REMOVED = 'Removed';
    const CHANGE_TYPE_FIXED = 'Fixed';
    const CHANGE_TYPE_SECURITY = 'Security';

    private $changeType;
    private $changeDetails;

    public function __construct()
    {
        $this->changeDetails = [];
    }

    public function getChangeType(): string
    {
        return $this->changeType;
    }

    public function setChangeType(string $changeType): self
    {
        $this->changeType = $changeType;
        return $this;
    }

    /**
     * @return ChangeDetails[]
     */
    public function getChangeDetails(): array
    {
        return $this->changeDetails;
    }

    /**
     * @param ChangeDetails[] $changeDetails
     *
     * @return $this
     */
    public function setChangeDetails(array $changeDetails): self
    {
        $this->changeDetails = $changeDetails;
        return $this;
    }

    public function addChangeDetails(ChangeDetails $changeDetails): self
    {
        $this->changeDetails[] = $changeDetails;
        return $this;
    }
}
