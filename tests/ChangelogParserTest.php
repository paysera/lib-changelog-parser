<?php
declare(strict_types=1);

namespace Paysera\Component\ChangelogParser\Tests;

use Paysera\Component\ChangelogParser\Service\ChangelogParser;
use Paysera\Component\ChangelogParser\Service\ChangelogConfiguration;
use Paysera\Component\ChangelogParser\Service\ValueExtractor;
use PHPUnit\Framework\TestCase;

class ChangelogParserTest extends TestCase
{
    /**
     * @var ChangelogParser
     */
    private $parser;

    protected function setUp()
    {
        $this->parser = new ChangelogParser(new ValueExtractor(new ChangelogConfiguration()));
    }

    /**
     * @param string $file
     * @param string $header
     * @param array $map
     *
     * @dataProvider dataProviderTestParse
     */
    public function testParse(string $file, string $header, array $map)
    {
        $changelog = $this->parser->parse(file_get_contents($file));

        $this->assertCount(count($map), $changelog->getVersions());
        $this->assertEquals($header, $changelog->getHeader());

        foreach ($changelog->getVersions() as $version) {
            $this->assertArrayHasKey($version->getVersion(), $map);
            $this->assertCount(count($map[$version->getVersion()]), $version->getChangeEntries());
            foreach ($version->getChangeEntries() as $changeEntry) {
                $this->assertArrayHasKey($changeEntry->getChangeType(), $map[$version->getVersion()]);
                $this->assertCount($map[$version->getVersion()][$changeEntry->getChangeType()], $changeEntry->getChangeDetails());
            }
        }
    }

    /**
     * @param string $file
     * @param array $dates
     *
     * @dataProvider dataProviderTestParseWithDates
     */
    public function testParseWithDates(string $file, array $dates)
    {
        $changelog = $this->parser->parse(file_get_contents($file));
        foreach ($changelog->getVersions() as $version) {
            $this->assertArrayHasKey($version->getVersion(), $dates);
            $this->assertSame($dates[$version->getVersion()], $version->getDate()->format('Y-m-d'));
        }
    }

    public function dataProviderTestParseWithDates()
    {
        return [
            'changelog_2.md' => [
                'file' => __DIR__ . '/fixtures/changelog_2.md',
                'dates' => [
                    'Unreleased' => '2018-02-01',
                    '2.7.0' => '2017-09-20',
                    '2.6.3' => '2016-04-11',
                    '2.6.2' => '2016-03-02',
                    '2.6.1' => '2016-02-12',
                    '2.6.0' => '2015-09-23',
                ]
            ],
        ];
    }

    public function dataProviderTestParse()
    {
        return [
            'changelog_2.md' => [
                'file' => __DIR__ . '/fixtures/changelog_2.md',
                'header' => '# Changelog

All Notable changes will be documented in this file',
                'map' => [
                    'Unreleased' => [
                        'Added' => 6,
                    ],
                    '2.7.0' => [
                        'Added' => 4,
                        'Fixed' => 6,
                        'Changed' => 2,
                    ],
                    '2.6.3' => [
                        'Added' => 1,
                        'Fixed' => 1,
                    ],
                    '2.6.2' => [
                        'Added' => 4,
                        'Fixed' => 2,
                    ],
                    '2.6.1' => [
                        'Added' => 1,
                        'Fixed' => 3,
                    ],
                    '2.6.0' => [
                        'Added' => 1,
                        'Fixed' => 4,
                    ],
                ],
            ],
            'changelog_1.md' => [
                'file' => __DIR__ . '/fixtures/changelog_1.md',
                'header' => '# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).',
                'map' => [
                    '10.2.0' => [
                        'Added' => 1,
                    ],
                    '10.1.1' => [
                        'Fixed' => 1,
                    ],
                    '10.1.0' => [
                        'Added' => 1,
                    ],
                    '10.0.1' => [
                        'Added' => 1,
                    ],
                    '10.0.0' => [
                        'Added' => 2,
                        'Removed' => 1,
                        'Changed' => 1,
                    ],
                    '9.1.1' => [
                        'Added' => 1,
                    ],
                    '9.0.0' => [
                        'Added' => 1,
                        'Changed' => 1,
                    ],
                    '8.0.0' => [
                        'Added' => 1,
                    ],
                    '7.0.2' => [
                        'Fixed' => 1,
                    ],
                    '7.0.1' => [
                        'Fixed' => 1,
                    ],
                    '7.0.0' => [
                        'Fixed' => 1,
                        'Changed' => 3,
                    ],
                    '6.0.0' => [
                        'Fixed' => 1,
                        'Added' => 2,
                    ],
                    '5.0.0' => [
                        'Changed' => 1,
                    ],
                    '4.1.0' => [
                        'Added' => 1,
                    ],
                    '4.0.0' => [
                        'Changed' => 1,
                    ],
                    '3.1.0' => [
                        'Added' => 2,
                        'Fixed' => 1,
                    ],
                ],
            ],
        ];
    }
}
