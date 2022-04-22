<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Data;

use MateuszMesek\DocumentDataIndexQueueApi\Data\MessageInterface;

class Message implements MessageInterface
{
    private array $dimensions;
    private array $entityIds;

    /**
     * @param array $dimensions
     * @param array $entityIds
     */
    public function __construct(
        array $dimensions = [],
        array $entityIds = []
    )
    {
        $this->dimensions = $dimensions;
        $this->entityIds = $entityIds;
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
