<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue;

use Magento\Framework\MessageQueue\PublisherInterface as MessageQueuePublisherInterface;
use MateuszMesek\DocumentDataIndexQueue\Command\GetTopicName;
use MateuszMesek\DocumentDataIndexQueue\Data\MessageFactory;
use MateuszMesek\DocumentDataIndexQueueApi\PublisherInterface;
use MateuszMesek\DocumentDataIndexQueueApi\TopicNameResolverInterface;
use Traversable;

class Publisher implements PublisherInterface
{
    private TopicNameResolverInterface $topicNameResolver;
    private GetTopicName $getTopicName;
    private MessageFactory $messageFactory;
    private MessageQueuePublisherInterface $publisher;

    public function __construct(
        TopicNameResolverInterface $topicNameResolver,
        GetTopicName $getTopicName,
        MessageFactory $messageFactory,
        MessageQueuePublisherInterface $publisher
    )
    {
        $this->topicNameResolver = $topicNameResolver;
        $this->getTopicName = $getTopicName;
        $this->messageFactory = $messageFactory;
        $this->publisher = $publisher;
    }

    public function publish(array $dimensions, Traversable $entityIds): void
    {
        $topicName = $this->getTopicName->execute(
            $this->topicNameResolver->resolve($dimensions)
        );

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
