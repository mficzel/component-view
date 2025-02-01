<?php

namespace PackageFactory\ComponentView\DummyClasses;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use PackageFactory\ComponentView\DummyInterfaces\IntegrationObjectInterface;
use PackageFactory\ComponentView\DummyInterfaces\NodeObjectInterface;

/**
 * @implements IntegrationObjectInterface<TextNodeObject>
 */
readonly class TextIntegrationObject implements IntegrationObjectInterface
{
    public function convertNodeToComponent(NodeObjectInterface $node): TextComponent
    {
        $text = $node->getText();
        if ($text !== null) {
            return TextComponent::create($text);
        }
        throw new \Exception('text is missing');
    }
}
