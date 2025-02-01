<?php

namespace PackageFactory\ComponentView\DummyClasses;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use PackageFactory\ComponentView\DummyInterfaces\NodeObjectInterface;

readonly class TextNodeObject implements NodeObjectInterface
{
    public function __construct(
        private NodeInterface $node
    ) {
    }

    public static function fromNode(NodeInterface $node): NodeObjectInterface
    {
        return new self($node);
    }

    public function getText(): ?string
    {
        $value = $this->node->getProperty('text');
        if (is_string($value)) {
            return $value;
        }
        return null;
    }
}
