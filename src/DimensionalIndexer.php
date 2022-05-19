<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue;

use Magento\Framework\Indexer\DimensionalIndexerInterface;
use MateuszMesek\DocumentDataIndexQueueApi\PublisherInterface;
use Psr\Log\LoggerInterface;
use Throwable;
use Traversable;

class DimensionalIndexer implements DimensionalIndexerInterface
{
    private LoggerInterface $logger;
    private PublisherInterface $publisher;

    public function __construct(
        LoggerInterface $logger,
        PublisherInterface $publisher
    )
    {
        $this->logger = $logger;
        $this->publisher = $publisher;
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
