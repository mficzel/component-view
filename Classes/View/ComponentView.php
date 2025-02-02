<?php

declare(strict_types=1);

namespace PackageFactory\ComponentView\View;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\Flow\Reflection\ClassReflection;
use PackageFactory\ComponentView\DummyInterfaces\IntegrationObjectInterface;
use PackageFactory\ComponentView\DummyInterfaces\NodeObjectInterface;

class ComponentView
{
    public function render(NodeInterface $node): string
    {
        $nodeObjectClass = $this->prepareNodeObjectClassName($node->getNodeType()->getName());
        $nodeObject = $this->instantiateNodeObject($nodeObjectClass, $node);

        $integrationObjectClass = $this->prepareIntegrationObjectClassName($node->getNodeType()->getName());
        $integrationObject = $this->instantiateIntegrationObject($integrationObjectClass, $node->getNodeType());

        return $integrationObject->convertComponent($nodeObject)->render();
    }

    /**
     * @template T of NodeObjectInterface
     * @param class-string<T> $nodeObjectClass
     * @param NodeInterface $node
     * @return NodeObjectInterface
     * @throws \Exception
     */
    private function instantiateNodeObject(string $nodeObjectClass, NodeInterface $node): NodeObjectInterface
    {
        if (class_exists($nodeObjectClass)) {
            $nodeObject = $nodeObjectClass::fromNode($node);
            if ($nodeObject instanceof NodeObjectInterface) {
                return $nodeObjectClass::fromNode($node);
            }
        }
        throw new \Exception(sprintf('Class %s does not exist or dies not iplement %s', $nodeObjectClass, NodeObjectInterface::class));
    }

    /**
     * @template T of IntegrationObjectInterface
     * @param class-string<T> $integrationObjectClass
     * @param NodeType $nodeType
     * @return T
     * @throws \Exception
     */
    private function instantiateIntegrationObject(string $integrationObjectClass, NodeType $nodeType): IntegrationObjectInterface
    {
        if (class_exists($integrationObjectClass)) {
            $integrationObject = new $integrationObjectClass();
            if ($integrationObject instanceof IntegrationObjectInterface) {
                return $integrationObject;
            }
        }
        throw new \Exception(sprintf('Class %s does not exist or dies not iplement %s', $integrationObjectClass, NodeObjectInterface::class));
    }

    /**
     * @param string $nodeTypeName
     * @return class-string<NodeObjectInterface>
     */
    private function prepareNodeObjectClassName(string $nodeTypeName): string
    {
        list($packageKey, $localName) = explode(':', $nodeTypeName);
        $packageKeyParts = explode('.', $packageKey);
        $localNameParts = explode('.', $localName);
        $className = implode('\\', $packageKeyParts) . '\\NodeTypes\\' . implode('\\', $localNameParts) . 'NodeObject';
        if (class_exists($className) && (new ClassReflection($className))->implementsInterface(NodeObjectInterface::class)) {
            /**
             * @phpstan-ignore return.type
             */
            return $className;
        }
        throw new \Exception(sprintf('%s does not implement %s as is required', $className, NodeObjectInterface::class));
    }

    /**
     * @param string $nodeTypeName
     * @return class-string<IntegrationObjectInterface>
     * @phpstan-ignore missingType.generics
     */
    private function prepareIntegrationObjectClassName(string $nodeTypeName): string
    {
        list($packageKey, $localName) = explode(':', $nodeTypeName);
        $packageKeyParts = explode('.', $packageKey);
        $localNameParts = explode('.', $localName);
        $className = implode('\\', $packageKeyParts) . '\\NodeTypes\\' . implode('\\', $localNameParts) . 'IntegrationObject';
        if (class_exists($className) && (new ClassReflection($className))->implementsInterface(IntegrationObjectInterface::class)) {
            /**
             * @phpstan-ignore return.type
             */
            return $className;
        }
        throw new \Exception(sprintf('%s does not implement %s as is required', $className, IntegrationObjectInterface::class));
    }
}
