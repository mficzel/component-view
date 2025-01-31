<?php

declare(strict_types=1);

namespace PackageFactory\ComponentView\View;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use PackageFactory\ComponentView\DummyInterfaces\IntegrationObjectInterface;
use PackageFactory\ComponentView\DummyInterfaces\NodeObjectInterface;

class ComponentView
{
    public function render(NodeInterface $node): string
    {
        $nodeTypeObject = $this->instantiateNodeTypeObject($node);
        $integrationObject = $this->instantiateIntegrationObject($node);
        return $integrationObject->convertNodeToComponent($nodeTypeObject)->render();
    }

    private function instantiateNodeTypeObject(NodeInterface $node): NodeObjectInterface
    {
        $nodeObjectClass = $this->prepareNodeTypeClassName($node->getNodeType()->getName(), 'NodeObject');
        if (class_exists($nodeObjectClass)) {
            $nodeObject = $nodeObjectClass::fromNode($node);
            if ($nodeObject instanceof NodeObjectInterface) {
                return $nodeObjectClass::fromNode($node);
            }
        }
        throw new \Exception(sprintf('Class %s does not exist or dies not iplement %s', $nodeObjectClass, NodeObjectInterface::class));
    }

    private function instantiateIntegrationObject(NodeInterface $node): IntegrationObjectInterface
    {
        $integrationObjectClass = $this->prepareNodeTypeClassName($node->getNodeType()->getName(), 'IntegrationObject');
        if (class_exists($integrationObjectClass)) {
            $integrationObject = new $integrationObjectClass();
            if ($integrationObject instanceof IntegrationObjectInterface) {
                return $integrationObject;
            }
        }
        throw new \Exception(sprintf('Class %s does not exist or dies not iplement %s', $integrationObjectClass, NodeObjectInterface::class));
    }

    private function prepareNodeTypeClassName(string $nodeTypeName, string $postfix): string
    {
        list($packageKey, $localName) = explode(':', $nodeTypeName);
        $packageKeyParts = explode('.', $packageKey);
        $localNameParts = explode('.', $localName);
        return implode('\\', $packageKeyParts) . '\\NodeTypes\\' . implode('\\', $localNameParts) . $postfix;
    }
}
