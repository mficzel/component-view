<?php

namespace PackageFactory\ComponentView\DummyClasses;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use PackageFactory\ComponentView\DummyInterfaces\ComponentInterface;
use PackageFactory\ComponentView\DummyInterfaces\NodeObjectInterface;

readonly class TextComponent implements ComponentInterface
{
    public function __construct(
        private string $text
    ) {
    }

    public static function create(string $text): TextComponent
    {
        return new self($text);
    }

    public function render(): string
    {
        return 'foo' . $this->text . 'bar';
    }
}
