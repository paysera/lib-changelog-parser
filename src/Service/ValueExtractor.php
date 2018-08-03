<?php
declare(strict_types=1);

namespace Paysera\Component\ChangelogParser\Service;

use DateTimeImmutable;
use DateTimeInterface;

class ValueExtractor
{
    const VERSION_LINE_PREFIX = '^#{2}\s?';
    const CHANGE_LINE_PREFIX = '^#{3}\s?';
    const DETAILS_LINE_PREFIX = '^-\s?';

    private $config;

    public function __construct(ChangelogConfiguration $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $contents
     * @return string
     */
    public function extractDetailsDescription(string $contents)
    {
        return $this->extractSingleValue(
            $contents,
            sprintf(
                '!%s((?:.|\n)*)$!im',
                self::DETAILS_LINE_PREFIX
            )
        );
    }

    public function extractChangeDetailsBlocks(string $contents)
    {
        return $this->extractMultiLineBlocks($contents, $this->getChangeDetailsBound());
    }

    /**
     * @param string $contents
     * @return string|null
     */
    public function extractChangeType(string $contents)
    {
        return $this->extractSingleValue(
            $contents,
            sprintf(
                '!%s(%s)$!im',
                self::CHANGE_LINE_PREFIX,
                $this->config->getChangeRegex()
            )
        );
    }

    public function extractChangeEntryBlocks(string $contents): array
    {
        return $this->extractMultiLineBlocks($contents, $this->getChangeEntryBound());
    }

    /**
     * @param string $contents
     * @return DateTimeInterface|null
     */
    public function extractVersionDate(string $contents)
    {
        $value = $this->extractSingleValue(
            $contents,
            sprintf(
                '!%s.+(%s)$!im',
                self::VERSION_LINE_PREFIX,
                $this->config->getDateRegex()
            )
        );
        return $value !== null ? DateTimeImmutable::createFromFormat($this->config->getDateFormat(), $value) : null;
    }

    public function extractVersionBlocks(string $contents): array
    {
        return $this->extractMultiLineBlocks($contents, $this->getVersionBound());
    }

    public function extractHeader(string $contents)
    {
        return $this->extractSingleValue(
            $contents,
            sprintf(
                '!%s(?=%s)!im',
                $this->config->getHeaderRegex(),
                $this->getVersionBound()
            )
        );
    }

    /**
     * @param string $contents
     * @return string|null
     */
    public function extractVersion(string $contents)
    {
        return $this->extractSingleValue(
            $contents,
            sprintf(
                '!%s%s!i',
                self::VERSION_LINE_PREFIX,
                sprintf($this->config->getVersionBlockRegex(), '(' . $this->config->getVersionNumberRegex() . ')')
            )
        );
    }

    private function extractSingleValue(string $contents, string $regex)
    {
        if (preg_match($regex, $contents,$matches) === 1) {
            return trim(isset($matches[2]) ? $matches[2] : $matches[1]);
        }

        return null;
    }

    private function extractMultiLineBlocks(string $contents, string $arg)
    {
        if (
            preg_match_all(
                sprintf('!(%1$s(?:.|\n)*?)(?=(?:%1$s)|\z)!im', $arg),
                $contents,
                $matches
            ) === 0
        ) {
            return [];
        }

        return array_map(
            function ($match) {
                return trim($match);
            },
            $matches[1]
        );
    }

    private function getChangeDetailsBound(): string
    {
        return sprintf(
            '%s%s',
            self::DETAILS_LINE_PREFIX,
            $this->config->getDetailsRegex()
        );
    }

    private function getChangeEntryBound(): string
    {
        return sprintf(
            '%s%s',
            self::CHANGE_LINE_PREFIX,
            $this->config->getChangeRegex()
        );
    }

    private function getVersionBound(): string
    {
        return sprintf(
            '%s%s',
            self::VERSION_LINE_PREFIX,
            sprintf($this->config->getVersionBlockRegex(), '(?:' . $this->config->getVersionNumberRegex() . ')')
        );
    }
}
