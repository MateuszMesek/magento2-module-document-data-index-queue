<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Model\Config;

use Magento\Framework\Communication\ConfigInterface as CommunicationConfigInterface;
use Magento\Framework\Config\ReaderInterface;
use MateuszMesek\DocumentDataApi\Model\Config\DocumentNamesInterface as ConfigInterface;
use MateuszMesek\DocumentDataIndexQueue\Model\Command\GetTopicName;
use MateuszMesek\DocumentDataIndexQueue\Model\TopicNamesProviderFactory;
use MateuszMesek\DocumentDataIndexQueueApi\Model\Data\MessageInterface;

class CommunicationReader implements ReaderInterface
{
    public function __construct(
        private readonly ConfigInterface           $config,
        private readonly TopicNamesProviderFactory $topicNamesProviderFactory,
        private readonly GetTopicName              $getTopicName,
        private readonly string                    $consumerType
    )
    {
    }

    public function read($scope = null): array
    {
        $topics = [];

        foreach ($this->config->getDocumentNames() as $documentName) {
            $topicNamesProvider = $this->topicNamesProviderFactory->create($documentName);

            foreach ($topicNamesProvider->getTopicNames() as $topicName) {
                $topicName = $this->getTopicName->execute($topicName);

                $topics[$topicName] = [
                    CommunicationConfigInterface::TOPIC_NAME => $topicName,
                    CommunicationConfigInterface::TOPIC_IS_SYNCHRONOUS => false,
                    CommunicationConfigInterface::TOPIC_REQUEST => MessageInterface::class,
                    CommunicationConfigInterface::TOPIC_REQUEST_TYPE => CommunicationConfigInterface::TOPIC_REQUEST_TYPE_CLASS,
                    CommunicationConfigInterface::TOPIC_RESPONSE => false,
                    CommunicationConfigInterface::TOPIC_HANDLERS => [
                        [
                            CommunicationConfigInterface::SCHEMA_METHOD_PARAMS => [],
                            CommunicationConfigInterface::SCHEMA_METHOD_RETURN_TYPE => null,
                            CommunicationConfigInterface::HANDLER_TYPE => $this->consumerType,
                            CommunicationConfigInterface::HANDLER_METHOD => 'consume'
                        ]
                    ]
                ];
            }
        }

        return [
            CommunicationConfigInterface::TOPICS => $topics
        ];
    }
}
