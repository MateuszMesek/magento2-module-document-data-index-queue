<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue;

use MateuszMesek\DocumentDataIndexIndexerApi\DimensionResolverInterface;
use MateuszMesek\DocumentDataIndexQueueApi\TopicNameResolverInterface;

class TopicNameResolver implements TopicNameResolverInterface
{
    private DimensionResolverInterface $documentNameResolver;
    private TopicNameResolverFactory $topicNameResolverFactory;

    public function __construct(
        DimensionResolverInterface $documentNameResolver,
        TopicNameResolverFactory $topicNameResolverFactory
    )
    {
        $this->documentNameResolver = $documentNameResolver;
        $this->topicNameResolverFactory = $topicNameResolverFactory;
    }

    public function resolve(array $dimensions): string
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);

        return $this->topicNameResolverFactory->get($documentName)->resolve($dimensions);
    }
}
