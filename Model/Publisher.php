<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Model;

use Magento\Framework\MessageQueue\PublisherInterface as MessageQueuePublisherInterface;
use MateuszMesek\DocumentDataIndexQueue\Model\Command\GetTopicName;
use MateuszMesek\DocumentDataIndexQueue\Model\Data\MessageFactory;
use MateuszMesek\DocumentDataIndexQueueApi\Model\PublisherInterface;
use MateuszMesek\DocumentDataIndexQueueApi\Model\TopicNameResolverInterface;
use Traversable;

class Publisher implements PublisherInterface
{
    public function __construct(
        private readonly TopicNameResolverInterface     $topicNameResolver,
        private readonly GetTopicName                   $getTopicName,
        private readonly MessageFactory                 $messageFactory,
        private readonly MessageQueuePublisherInterface $publisher
    )
    {
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
