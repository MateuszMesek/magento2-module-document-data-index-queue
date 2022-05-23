<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexQueueApi\Config\TopicNamesProviderInterface as ConfigInterface;
use MateuszMesek\DocumentDataIndexQueueApi\TopicNamesProviderInterface;

class TopicNamesProviderFactory
{
    private Config $config;
    private ObjectManagerInterface $objectManager;

    public function __construct(
        ConfigInterface        $config,
        ObjectManagerInterface $objectManager
    )
    {
        $this->config = $config;
        $this->objectManager = $objectManager;
    }

    public function create(string $documentName): TopicNamesProviderInterface
    {
        $type = $this->config->getTopicNamesProvider($documentName);

        if (null === $type) {
            return $this->objectManager->create(
                TopicNamesProvider::class,
                [
                    'documentName' => $documentName
                ]
            );
        }

        $topicNameProvider = $this->objectManager->create($type);

        if (!$topicNameProvider instanceof TopicNamesProviderInterface) {
            $interfaceName = TopicNamesProviderInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $topicNameProvider;
    }
}
