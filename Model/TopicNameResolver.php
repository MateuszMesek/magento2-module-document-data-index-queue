<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Model;

use MateuszMesek\DocumentDataIndexIndexerApi\Model\DimensionResolverInterface;
use MateuszMesek\DocumentDataIndexQueue\Model\TopicNameResolverFactory;
use MateuszMesek\DocumentDataIndexQueueApi\Model\TopicNameResolverInterface;

class TopicNameResolver implements TopicNameResolverInterface
{
    public function __construct(
        private readonly DimensionResolverInterface $documentNameResolver,
        private readonly TopicNameResolverFactory   $topicNameResolverFactory
    )
    {
    }

    public function resolve(array $dimensions): string
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);

        return $this->topicNameResolverFactory->get($documentName)->resolve($dimensions);
    }
}
