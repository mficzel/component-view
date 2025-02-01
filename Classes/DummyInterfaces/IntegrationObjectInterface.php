<?php

declare(strict_types=1);

namespace PackageFactory\ComponentView\DummyInterfaces;

use Neos\Flow\Annotations as Flow;

/**
 * @template T of NodeObjectInterface
 */
#[Flow\Proxy(false)]
interface IntegrationObjectInterface
{
    /**
     * @param T $node
     * @return ComponentInterface
     */
    public function convertNodeToComponent(NodeObjectInterface $node): ComponentInterface;
}
