<?php
declare(strict_types=1);

namespace Paysera\Component\ChangelogParser\Entity;

class Changelog
{
    private $header;
    private $versions;

    public function __construct()
    {
        $this->versions = [];
    }

    /**
     * @return string|null
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param string|null $header
     *
     * @return $this
     */
    public function setHeader($header): self
    {
        $this->header = $header;
        return $this;
    }

    /**
     * @return VersionInfo[]
     */
    public function getVersions(): array
    {
        return $this->versions;
    }

    /**
     * @param VersionInfo[] $versions
     *
     * @return $this
     */
    public function setVersions(array $versions): self
    {
        $this->versions = $versions;
        return $this;
    }

    public function addVersion(VersionInfo $version): self
    {
        $this->versions[] = $version;
        return $this;
    }
}
