<?php
declare(strict_types=1);

namespace Paysera\Component\ChangelogParser\Service;

use Paysera\Component\ChangelogParser\Entity\Changelog;
use Twig_Environment;

class ChangelogDumper
{
    private $config;
    private $twig;
    private $templateName;

    public function __construct(
        ChangelogConfiguration $config,
        Twig_Environment $twig,
        string $templateName
    ) {
        $this->config = $config;
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
