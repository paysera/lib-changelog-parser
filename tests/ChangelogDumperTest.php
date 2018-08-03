<?php
declare(strict_types=1);

namespace Paysera\Component\ChangelogParser\Tests;

use Paysera\Component\ChangelogParser\Service\ChangelogDumper;
use Paysera\Component\ChangelogParser\Service\ChangelogParser;
use Paysera\Component\ChangelogParser\Service\ChangelogConfiguration;
use Paysera\Component\ChangelogParser\Service\ValueExtractor;
use PHPUnit\Framework\TestCase;
use Twig_Environment;
use Twig_Loader_Array;

class ChangelogDumperTest extends TestCase
{
    /**
     * @var ChangelogDumper
     */
    private $dumper;

    /**
     * @var ChangelogParser
     */
    private $parser;

    protected function setUp()
    {
        $this->dumper = new ChangelogDumper(
            new ChangelogConfiguration(),
            new Twig_Environment(
                new Twig_Loader_Array([
                    'changelog.md' => file_get_contents(__DIR__ . '/../src/Template/changelog.md.twig'),
                ])
            ),
            'changelog.md'
        );

        $this->parser = new ChangelogParser(new ValueExtractor(new ChangelogConfiguration()));
    }

    /**
     * @param string $contents
     *
     * @dataProvider dataProviderTestDump
     */
    public function testDump(string $contents)
    {
        $changelog = $this->parser->parse($contents);
        $output = $this->dumper->dump($changelog);

        $this->assertEquals($contents, $output);
    }

    public function dataProviderTestDump()
    {
        return [
            [file_get_contents(__DIR__ . '/fixtures/changelog_1.md')],
        ];
    }
}
