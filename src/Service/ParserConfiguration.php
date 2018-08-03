<?php
declare(strict_types=1);

namespace Paysera\Component\ChangelogParser\Service;

class ParserConfiguration
{
    private $includeHeader;
    private $header;
    private $versionBlockRegex;
    private $versionNumberRegex;
    private $dateRegex;
    private $headerRegex;
    private $dateFormat;
    private $changeRegex;
    private $detailsRegex;
    private $versionPattern;

    public function __construct()
    {
        $this->includeHeader = true;
        $this->header = <<<'HEADER'
# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

HEADER;

        $this->versionNumberRegex = 'Unreleased|(?:\d+\.\d+\.\d+)';
        $this->versionBlockRegex = '\[?%s\]?';
        $this->headerRegex = '^(#\s?Changelog(?:.|\n)*?)';
        $this->dateRegex = '\d{4}-\d{2}-\d{2}';
        $this->dateFormat = 'Y-m-d';
        $this->changeRegex = '\w+';
        $this->detailsRegex = '';
        $this->versionPattern = '%version%';
    }

    public function includeHeader(): bool
    {
        return $this->includeHeader;
    }

    public function setIncludeHeader(bool $includeHeader): self
    {
        $this->includeHeader = $includeHeader;
        return $this;
    }

    public function getHeader(): string
    {
        return $this->header;
    }

    public function setHeader(string $header): self
    {
        $this->header = $header;
        return $this;
    }

    public function getVersionBlockRegex(): string
    {
        return $this->versionBlockRegex;
    }

    public function setVersionBlockRegex(string $versionBlockRegex): self
    {
        $this->versionBlockRegex = $versionBlockRegex;
        return $this;
    }

    public function getDateRegex(): string
    {
        return $this->dateRegex;
    }

    public function setDateRegex(string $dateRegex): self
    {
        $this->dateRegex = $dateRegex;
        return $this;
    }

    public function getHeaderRegex(): string
    {
        return $this->headerRegex;
    }

    public function setHeaderRegex(string $headerRegex): self
    {
        $this->headerRegex = $headerRegex;
        return $this;
    }

    public function getDateFormat(): string
    {
        return $this->dateFormat;
    }

    public function setDateFormat(string $dateFormat): self
    {
        $this->dateFormat = $dateFormat;
        return $this;
    }

    public function getChangeRegex(): string
    {
        return $this->changeRegex;
    }

    public function setChangeRegex(string $changeRegex): self
    {
        $this->changeRegex = $changeRegex;
        return $this;
    }

    public function getDetailsRegex(): string
    {
        return $this->detailsRegex;
    }

    public function setDetailsRegex(string $detailsRegex): self
    {
        $this->detailsRegex = $detailsRegex;
        return $this;
    }

    public function getVersionPattern(): string
    {
        return $this->versionPattern;
    }

    public function setVersionPattern(string $versionPattern): self
    {
        $this->versionPattern = $versionPattern;
        return $this;
    }

    public function getVersionNumberRegex(): string
    {
        return $this->versionNumberRegex;
    }

    public function setVersionNumberRegex(string $versionNumberRegex): self
    {
        $this->versionNumberRegex = $versionNumberRegex;
        return $this;
    }
}
