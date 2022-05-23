<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexQueue\TopicNameResolver\AutoResolver;
use MateuszMesek\DocumentDataIndexQueueApi\TopicNameResolverInterface;
use MateuszMesek\DocumentDataIndexQueueApi\Config\TopicNameResolverInterface as ConfigInterface;

class TopicNameResolverFactory
{
    private ConfigInterface $config;
    private ObjectManagerInterface $objectManager;
    private string $defaultTopicNameResolver;
    /**
     * @var TopicNameResolverInterface[]
     */
    private array $instances = [];

    public function __construct(
        ConfigInterface        $config,
        ObjectManagerInterface $objectManager,
        string $defaultTopicNameResolver = AutoResolver::class
    )
    {
        $this->config = $config;
        $this->objectManager = $objectManager;
        $this->defaultTopicNameResolver = $defaultTopicNameResolver;
    }

    public function create(string $documentName): TopicNameResolverInterface
    {
        $type = $this->config->getTopicNameResolver($documentName);

        if (null === $type) {
            $type = $this->defaultTopicNameResolver;
        }

        $topicNameResolver = $this->objectManager->create($type);

        if (!$topicNameResolver instanceof TopicNameResolverInterface) {
            $interfaceName = TopicNameResolverInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $topicNameResolver;
    }

    public function get(string $documentName): TopicNameResolverInterface
    {
        if (!isset($this->instances[$documentName])) {
            $this->instances[$documentName] = $this->create($documentName);
        }

        return $this->instances[$documentName];
    }
}
