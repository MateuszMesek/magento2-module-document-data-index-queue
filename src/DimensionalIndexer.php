<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue;

use Magento\Framework\Indexer\DimensionalIndexerInterface;
use MateuszMesek\DocumentDataIndexQueueApi\PublisherInterface;
use Traversable;

class DimensionalIndexer implements DimensionalIndexerInterface
{
    private PublisherInterface $publisher;

    public function __construct(
        PublisherInterface $publisher
    )
    {
        $this->publisher = $publisher;
    }

    public function executeByDimensions(array $dimensions, Traversable $entityIds)
    {
        $this->publisher->publish($dimensions, $entityIds);
    }
}
