<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\DimensionalIndexer;

use Magento\Framework\Indexer\DimensionalIndexerInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Throwable;
use Traversable;

class Fallback implements DimensionalIndexerInterface
{
    private LoggerInterface $logger;
    private DimensionalIndexerInterface $standardDimensionalIndexer;
    private DimensionalIndexerInterface $fallbackDimensionalIndexer;
    private string $message;

    public function __construct(
        LoggerInterface $logger,
        DimensionalIndexerInterface $standardDimensionalIndexer,
        DimensionalIndexerInterface $fallbackDimensionalIndexer,
        string $message
    )
    {
        $this->logger = $logger;
        $this->standardDimensionalIndexer = $standardDimensionalIndexer;
        $this->fallbackDimensionalIndexer = $fallbackDimensionalIndexer;
        $this->message = $message;
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
