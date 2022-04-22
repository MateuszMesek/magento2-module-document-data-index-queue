<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Config;

use Magento\Framework\Config\ReaderInterface;
use MateuszMesek\DocumentDataApi\Config\DocumentNamesInterface as ConfigInterface;
use MateuszMesek\DocumentDataIndexIndexer\IndexNamesProviderFactory;
use MateuszMesek\DocumentDataIndexQueue\Command\GetTopicName;

class PublisherReader implements ReaderInterface
{
    private ConfigInterface $config;
    private IndexNamesProviderFactory $indexNamesProviderFactory;
    private GetTopicName $getTopicName;

    public function __construct(
        ConfigInterface           $config,
        IndexNamesProviderFactory $indexNamesProviderFactory,
        GetTopicName              $getTopicName
    )
    {
        $this->config = $config;
        $this->indexNamesProviderFactory = $indexNamesProviderFactory;
        $this->getTopicName = $getTopicName;
    }

    public function read($scope = null)
    {
        $topics = [];

        foreach ($this->config->getDocumentNames() as $documentName) {
            $indexNamesProvider = $this->indexNamesProviderFactory->create($documentName);

            foreach ($indexNamesProvider->getIndexNames() as $indexName) {
                $topicName = $this->getTopicName->execute($indexName);

                $topics[$topicName] = [
                    'topic' => $topicName,
                    'disabled' => false,
                    'connections' => [
                        'amqp' => [
                            'name' => 'amqp',
                            'exchange' => 'magento',
                            'disabled' => false,
                        ]
                    ]
                ];
            }
        }

        return $topics;
    }
}
