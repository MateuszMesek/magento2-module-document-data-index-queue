<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue;

use Magento\Framework\MessageQueue\PublisherInterface as MessageQueuePublisherInterface;
use MateuszMesek\DocumentDataIndexQueue\Data\MessageFactory;
use MateuszMesek\DocumentDataIndexQueueApi\PublisherInterface;
use MateuszMesek\DocumentDataIndexQueueApi\TopicNameResolverInterface;
use Traversable;

class Publisher implements PublisherInterface
{
    private TopicNameResolverInterface $topicNameResolver;
    private MessageFactory $messageFactory;
    private MessageQueuePublisherInterface $publisher;

    public function __construct(
        TopicNameResolverInterface $topicNameResolver,
        MessageFactory $messageFactory,
        MessageQueuePublisherInterface $publisher
    )
    {
        $this->topicNameResolver = $topicNameResolver;
        $this->messageFactory = $messageFactory;
        $this->publisher = $publisher;
    }

    public function publish(array $dimensions, Traversable $entityIds): void
    {
        $topicName = $this->topicNameResolver->resolve($dimensions);

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
