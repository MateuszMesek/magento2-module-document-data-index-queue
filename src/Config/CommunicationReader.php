<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Config;

use Magento\Framework\Communication\ConfigInterface as CommunicationConfigInterface;
use Magento\Framework\Config\ReaderInterface;
use MateuszMesek\DocumentDataApi\Config\DocumentNamesInterface as ConfigInterface;
use MateuszMesek\DocumentDataIndexIndexer\IndexNamesProviderFactory;
use MateuszMesek\DocumentDataIndexQueue\Command\GetTopicName;
use MateuszMesek\DocumentDataIndexQueueApi\Data\MessageInterface;

class CommunicationReader implements ReaderInterface
{
    private ConfigInterface $config;
    private IndexNamesProviderFactory $indexNamesProviderFactory;
    private GetTopicName $getTopicName;
    private string $consumerType;

    public function __construct(
        ConfigInterface           $config,
        IndexNamesProviderFactory $indexNamesProviderFactory,
        GetTopicName              $getTopicName,
        string $consumerType
    )
    {
        $this->config = $config;
        $this->indexNamesProviderFactory = $indexNamesProviderFactory;
        $this->getTopicName = $getTopicName;
        $this->consumerType = $consumerType;
    }

    public function read($scope = null): array
    {
        $topics = [];

        foreach ($this->config->getDocumentNames() as $documentName) {
            $indexNamesProvider = $this->indexNamesProviderFactory->create($documentName);

            foreach ($indexNamesProvider->getIndexNames() as $indexName) {
                $topicName = $this->getTopicName->execute($indexName);

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
