<?php
declare(strict_types=1);

namespace Paysera\Component\ChangelogParser\Tests;

use Paysera\Component\ChangelogParser\Service\ChangelogParser;
use Paysera\Component\ChangelogParser\Service\ParserConfiguration;
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
        $this->parser = new ChangelogParser(new ValueExtractor(new ParserConfiguration()));
    }

    /**
     * @param string $file
     * @param array $map
     *
     * @dataProvider dataProviderTestParse
     */
    public function testParse(string $file, array $map)
    {
        $changelog = $this->parser->parse(file_get_contents($file));

        $this->assertCount(count($map), $changelog->getVersions());
        foreach ($changelog->getVersions() as $version) {
            $this->assertArrayHasKey($version->getVersion(), $map);
            $this->assertCount(count($map[$version->getVersion()]), $version->getChangeEntries());
            foreach ($version->getChangeEntries() as $changeEntry) {
                $this->assertArrayHasKey($changeEntry->getChangeType(), $map[$version->getVersion()]);
                $this->assertCount($map[$version->getVersion()][$changeEntry->getChangeType()], $changeEntry->getChangeDetails());
            }
        }
    }

    public function dataProviderTestParse()
    {
        return [
            [
                'file' => __DIR__ . '/fixtures/changelog_1.md',
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
                ]
            ]
        ];
    }
}
