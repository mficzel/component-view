<?php

declare(strict_types=1);

namespace PackageFactory\ComponentView\DummyInterfaces;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
interface IntegrationObjectInterface
{
    public function convertNodeToComponent(NodeObjectInterface $node): ComponentInterface;
}
