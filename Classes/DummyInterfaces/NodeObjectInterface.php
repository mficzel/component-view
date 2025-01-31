<?php

declare(strict_types=1);

namespace PackageFactory\ComponentView\DummyInterfaces;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
interface NodeObjectInterface
{
    public static function fromNode(NodeInterface $node): self;
}
