<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue;

use Magento\Framework\MessageQueue\PublisherInterface as MessageQueuePublisherInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\IndexNameResolverInterface;
use MateuszMesek\DocumentDataIndexQueue\Command\GetTopicName;
use MateuszMesek\DocumentDataIndexQueue\Data\MessageFactory;
use MateuszMesek\DocumentDataIndexQueueApi\PublisherInterface;
use Traversable;

class Publisher implements PublisherInterface
{
    private IndexNameResolverInterface $indexNameResolver;
    private GetTopicName $getTopicName;
    private MessageFactory $messageFactory;
    private MessageQueuePublisherInterface $publisher;

    public function __construct(
        IndexNameResolverInterface $indexNameResolver,
        GetTopicName $getTopicName,
        MessageFactory $messageFactory,
        MessageQueuePublisherInterface $publisher
    )
    {
        $this->indexNameResolver = $indexNameResolver;
        $this->getTopicName = $getTopicName;
        $this->messageFactory = $messageFactory;
        $this->publisher = $publisher;
    }

    public function publish(array $dimensions, Traversable $entityIds): void
    {
        $indexName = $this->indexNameResolver->resolve($dimensions);
        $topicName = $this->getTopicName->execute($indexName);

        foreach ($entityIds as $entityId) {
            $message = $this->messageFactory->create([
                'dimensions' => $dimensions,
                'entityIds' => [$entityId]
            ]);

            $this->publisher->publish(
                $topicName,
                $message
            );
        }
    }
}
