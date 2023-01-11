<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Model;

use MateuszMesek\DocumentDataIndexIndexer\Model\Action\DimensionProviderFactory;
use MateuszMesek\DocumentDataIndexQueueApi\Model\TopicNameResolverInterface;
use MateuszMesek\DocumentDataIndexQueueApi\Model\TopicNamesProviderInterface;
use Traversable;

class TopicNamesProvider implements TopicNamesProviderInterface
{
    public function __construct(
        private readonly DimensionProviderFactory   $dimensionProviderFactory,
        private readonly TopicNameResolverInterface $topicNameResolver,
        private readonly string                     $documentName
    )
    {
    }

    public function getTopicNames(): Traversable
    {
        $dimensionProvider = $this->dimensionProviderFactory->create($this->documentName);

        foreach ($dimensionProvider->getIterator() as $dimensions) {
            yield $this->topicNameResolver->resolve($dimensions);
        }
    }
}
