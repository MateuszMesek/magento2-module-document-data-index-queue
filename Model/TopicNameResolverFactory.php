<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Model;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexQueue\Model\TopicNameResolver\AutoResolver;
use MateuszMesek\DocumentDataIndexQueueApi\Model\TopicNameResolverInterface;
use MateuszMesek\DocumentDataIndexQueueApi\Model\Config\TopicNameResolverInterface as ConfigInterface;

class TopicNameResolverFactory
{/**/
    /**
     * @var TopicNameResolverInterface[]
     */
    private array $instances = [];

    public function __construct(
        private readonly ConfigInterface        $config,
        private readonly ObjectManagerInterface $objectManager,
        private readonly string                 $defaultTopicNameResolver = AutoResolver::class
    )
    {
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
