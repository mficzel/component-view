<?php

declare(strict_types=1);

namespace PackageFactory\ComponentView\View;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\NodeType;
use PackageFactory\ComponentView\DummyInterfaces\IntegrationObjectInterface;
use PackageFactory\ComponentView\DummyInterfaces\NodeObjectInterface;

class ComponentView
{
    public function render(NodeInterface $node): string
    {
        $nodeObject = $this->instantiateNodeObject($node);
        $integrationObject = $this->instantiateIntegrationObject($node->getNodeType());
        return $integrationObject->convertNodeToComponent($nodeObject)->render();
    }

    private function instantiateNodeObject(NodeInterface $node): NodeObjectInterface
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

    private function instantiateIntegrationObject(NodeType $nodeType): IntegrationObjectInterface
    {
        $integrationObjectClass = $this->prepareNodeTypeClassName($nodeType->getName(), 'IntegrationObject');
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
