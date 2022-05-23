<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue;

use Magento\Framework\Config\DataInterface;
use MateuszMesek\DocumentDataIndexQueueApi\Config\TopicNameResolverInterface;
use MateuszMesek\DocumentDataIndexQueueApi\Config\TopicNamesProviderInterface;

class Config implements TopicNamesProviderInterface, TopicNameResolverInterface
{
    private DataInterface $data;

    public function __construct(
        DataInterface $data
    )
    {
        $this->data = $data;
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
