<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Model\DimensionalIndexer;

use Magento\Framework\Indexer\DimensionalIndexerInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Throwable;
use Traversable;

class Fallback implements DimensionalIndexerInterface
{
    public function __construct(
        private readonly LoggerInterface             $logger,
        private readonly DimensionalIndexerInterface $standardDimensionalIndexer,
        private readonly DimensionalIndexerInterface $fallbackDimensionalIndexer,
        private readonly string                      $message
    )
    {
    }

    public function executeByDimensions(array $dimensions, Traversable $entityIds)
    {
        try {
            $this->standardDimensionalIndexer->executeByDimensions($dimensions, clone $entityIds);
        } catch (Throwable $exception) {
            $this->logger->emergency(new RuntimeException($this->message, 0, $exception));

            $this->fallbackDimensionalIndexer->executeByDimensions($dimensions, clone $entityIds);
        }
    }
}
