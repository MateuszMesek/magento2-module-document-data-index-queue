<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Model;

use Magento\Framework\Config\DataInterface;
use MateuszMesek\DocumentDataIndexQueueApi\Model\Config\TopicNameResolverInterface;
use MateuszMesek\DocumentDataIndexQueueApi\Model\Config\TopicNamesProviderInterface;

class Config implements TopicNamesProviderInterface, TopicNameResolverInterface
{
    public function __construct(
        private readonly DataInterface $data
    )
    {
    }

    public function getTopicNamesProvider(string $documentName): ?string
    {
        return $this->data->get("$documentName/topicNamesProvider");
    }

    public function getTopicNameResolver(string $documentName): ?string
    {
        return $this->data->get("$documentName/topicNameResolver");
    }
}
