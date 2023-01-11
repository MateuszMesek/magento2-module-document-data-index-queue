<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Model\Config;

use Magento\Framework\Config\ReaderInterface;
use MateuszMesek\DocumentDataApi\Model\Config\DocumentNamesInterface as ConfigInterface;
use MateuszMesek\DocumentDataIndexQueue\Model\Command\GetTopicName;
use MateuszMesek\DocumentDataIndexQueue\Model\TopicNamesProviderFactory;

class PublisherReader implements ReaderInterface
{
    public function __construct(
        private readonly ConfigInterface           $config,
        private readonly TopicNamesProviderFactory $topicNamesProviderFactory,
        private readonly GetTopicName              $getTopicName
    )
    {
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
