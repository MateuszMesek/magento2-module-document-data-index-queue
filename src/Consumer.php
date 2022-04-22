<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue;

use ArrayIterator;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Indexer\DimensionalIndexerInterface;
use MateuszMesek\DocumentDataIndexQueueApi\ConsumerInterface;
use MateuszMesek\DocumentDataIndexQueueApi\Data\MessageInterface;

class Consumer implements ConsumerInterface
{
    private ResourceConnection $resourceConnection;
    private DimensionalIndexerInterface $dimensionalIndexer;

    public function __construct(
        ResourceConnection $resourceConnection,
        DimensionalIndexerInterface $dimensionalIndexer
    )
    {
        $this->resourceConnection = $resourceConnection;
        $this->dimensionalIndexer = $dimensionalIndexer;
    }

    public function consume(MessageInterface $message): void
    {
        $this->resourceConnection->getConnection()->query('SET SESSION transaction_isolation = "READ-COMMITTED"');

        $dimensions = [];

        foreach ($message->getDimensions() as $dimension) {
            $dimensions[$dimension->getName()] = $dimension;
        }

        $this->dimensionalIndexer->executeByDimensions(
            $dimensions,
            new ArrayIterator($message->getEntityIds())
        );
    }
}
