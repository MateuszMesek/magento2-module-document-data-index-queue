<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Model;

use Magento\Framework\Indexer\DimensionalIndexerInterface;
use MateuszMesek\DocumentDataIndexQueueApi\Model\PublisherInterface;
use Psr\Log\LoggerInterface;
use Throwable;
use Traversable;

class DimensionalIndexer implements DimensionalIndexerInterface
{
    public function __construct(
        private readonly LoggerInterface    $logger,
        private readonly PublisherInterface $publisher
    )
    {
    }

    public function executeByDimensions(array $dimensions, Traversable $entityIds)
    {
        try {
            $this->publisher->publish($dimensions, $entityIds);
        } catch (Throwable $exception) {
            $this->logger->emergency($exception);
        }
    }
}
