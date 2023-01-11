<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Model\Data;

use MateuszMesek\DocumentDataIndexQueueApi\Model\Data\MessageInterface;

class Message implements MessageInterface
{
    /**
     * @param array $dimensions
     * @param array $entityIds
     */
    public function __construct(
        private array $dimensions = [],
        private array $entityIds = []
    )
    {
    }

    public function getDimensions(): array
    {
        return $this->dimensions;
    }

    public function getEntityIds(): array
    {
        return $this->entityIds;
    }

    public function setDimensions(array $dimensions): void
    {
        $this->dimensions = $dimensions;
    }

    public function setEntityIds(array $entityIds): void
    {
        $this->entityIds = $entityIds;
    }
}
