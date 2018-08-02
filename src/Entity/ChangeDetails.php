<?php
declare(strict_types=1);

namespace Paysera\Component\ChangelogParser\Entity;

class ChangeDetails
{
    private $description;

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
}
