<?php

declare(strict_types=1);

namespace PackageFactory\ComponentView\DummyInterfaces;

use Neos\Flow\Annotations as Flow;

/**
 * @template-contravariant T
 */
#[Flow\Proxy(false)]
interface IntegrationObjectInterface
{
    /**
     * @param T $node
     * @return ComponentInterface
     */
    public function convertComponent(mixed $node): ComponentInterface;
}
