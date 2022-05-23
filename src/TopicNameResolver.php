<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue;

use MateuszMesek\DocumentDataIndexIndexerApi\IndexNameResolverInterface;
use MateuszMesek\DocumentDataIndexQueue\Command\GetTopicName;
use MateuszMesek\DocumentDataIndexQueueApi\TopicNameResolverInterface;

class TopicNameResolver implements TopicNameResolverInterface
{
    private IndexNameResolverInterface $indexNameResolver;
    private GetTopicName $getTopicName;

    public function __construct(
        IndexNameResolverInterface $indexNameResolver,
        GetTopicName $getTopicName
    )
    {
        $this->indexNameResolver = $indexNameResolver;
        $this->getTopicName = $getTopicName;
    }

    public function resolve(array $dimensions): string
    {
        $indexName = $this->indexNameResolver->resolve($dimensions);

        return $this->getTopicName->execute($indexName);
    }
}
