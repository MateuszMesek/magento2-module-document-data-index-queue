<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Model;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexQueue\Model\Config;
use MateuszMesek\DocumentDataIndexQueue\Model\TopicNamesProvider;
use MateuszMesek\DocumentDataIndexQueueApi\Model\Config\TopicNamesProviderInterface as ConfigInterface;
use MateuszMesek\DocumentDataIndexQueueApi\Model\TopicNamesProviderInterface;

class TopicNamesProviderFactory
{
    public function __construct(
        private readonly ConfigInterface        $config,
        private readonly ObjectManagerInterface $objectManager
    )
    {
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
