<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue;

use MateuszMesek\DocumentDataIndexIndexer\Action\DimensionProviderFactory;
use MateuszMesek\DocumentDataIndexQueueApi\TopicNameResolverInterface;
use MateuszMesek\DocumentDataIndexQueueApi\TopicNamesProviderInterface;
use Traversable;

class TopicNamesProvider implements TopicNamesProviderInterface
{
    private DimensionProviderFactory $dimensionProviderFactory;
    private TopicNameResolverInterface $topicNameResolver;
    private string $documentName;

    public function __construct(
        DimensionProviderFactory $dimensionProviderFactory,
        TopicNameResolverInterface $topicNameResolver,
        string $documentName
    )
    {
        $this->dimensionProviderFactory = $dimensionProviderFactory;
        $this->topicNameResolver = $topicNameResolver;
        $this->documentName = $documentName;
    }

    public function getTopicNames(): Traversable
    {
        $dimensionProvider = $this->dimensionProviderFactory->create($this->documentName);

        foreach ($dimensionProvider->getIterator() as $dimensions) {
            yield $this->topicNameResolver->resolve($dimensions);
        }
    }
}
