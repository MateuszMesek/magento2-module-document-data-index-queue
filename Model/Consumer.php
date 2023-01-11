<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Model;

use ArrayIterator;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Indexer\DimensionalIndexerInterface;
use MateuszMesek\DocumentDataIndexQueueApi\Model\ConsumerInterface;
use MateuszMesek\DocumentDataIndexQueueApi\Model\Data\MessageInterface;

class Consumer implements ConsumerInterface
{
    public function __construct(
        private readonly ResourceConnection          $resourceConnection,
        private readonly DimensionalIndexerInterface $dimensionalIndexer
    )
    {
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
