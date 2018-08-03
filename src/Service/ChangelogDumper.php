<?php
declare(strict_types=1);

namespace Paysera\Component\ChangelogParser\Service;

use Paysera\Component\ChangelogParser\Entity\Changelog;
use Twig_Environment;
use Twig_Loader_Array;

class ChangelogDumper
{
    private $config;
    private $twig;
    private $templateName;

    public function __construct(
        ChangelogConfiguration $config,
        Twig_Environment $twig = null,
        string $templateName = null
    ) {
        $this->config = $config;

        if ($templateName === null) {
            $templateName = 'changelog.md';
        }
        if ($twig === null) {
            $twig = new Twig_Environment(
                new Twig_Loader_Array([
                    'changelog.md' => file_get_contents(__DIR__ . '/../Template/changelog.md.twig'),
                ])
            );
        }

        $this->twig = $twig;
        $this->templateName = $templateName;
    }

    public function dump(Changelog $changelog): string
    {
        $contents = $this->twig->render(
            $this->templateName,
            [
                'changelog' => $changelog,
                'config' => $this->config,
            ]
        );
        return trim($contents) . "\n";
    }
}
