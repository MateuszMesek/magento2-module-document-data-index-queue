<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Config;

use Magento\Framework\Config\ReaderInterface;
use MateuszMesek\DocumentDataApi\Config\DocumentNamesInterface as ConfigInterface;
use MateuszMesek\DocumentDataIndexQueue\Command\GetTopicName;
use MateuszMesek\DocumentDataIndexQueue\TopicNamesProviderFactory;

class PublisherReader implements ReaderInterface
{
    private ConfigInterface $config;
    private TopicNamesProviderFactory $topicNamesProviderFactory;
    private GetTopicName $getTopicName;

    public function __construct(
        ConfigInterface           $config,
        TopicNamesProviderFactory $topicNamesProviderFactory,
        GetTopicName              $getTopicName
    )
    {
        $this->config = $config;
        $this->topicNamesProviderFactory = $topicNamesProviderFactory;
        $this->getTopicName = $getTopicName;
    }

    public function read($scope = null)
    {
        $topics = [];

        foreach ($this->config->getDocumentNames() as $documentName) {
            $topicNamesProvider = $this->topicNamesProviderFactory->create($documentName);

            foreach ($topicNamesProvider->getTopicNames() as $topicName) {
                $topicName = $this->getTopicName->execute($topicName);

                $topics[$topicName] = [
                    'topic' => $topicName,
                    'connection' => [
                        'name' => 'amqp',
                        'exchange' => 'magento',
                        'disabled' => false,
                    ],
                    'disabled' => false
                ];
            }
        }

        return $topics;
    }
}
