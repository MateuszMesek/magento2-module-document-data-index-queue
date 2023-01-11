<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Model\Config;

use Magento\Framework\Config\ReaderInterface;
use Magento\Framework\ObjectManager\TMap;
use Magento\Framework\ObjectManager\TMapFactory;

class CompositeReader implements ReaderInterface
{
    /* @var \Magento\Framework\ObjectManager\TMap|\Magento\Framework\Config\ReaderInterface[] */
    private TMap $readers;

    public function __construct(
        TMapFactory $TMapFactory,
        array       $readers = []
    )
    {
        $this->readers = $TMapFactory->createSharedObjectsMap([
            'type' => ReaderInterface::class,
            'array' => $readers
        ]);
    }

    public function read($scope = null)
    {
        $results = [];

        foreach ($this->readers as $reader) {
            $results[] = $reader->read($scope);
        }

        return array_merge_recursive(...$results);
    }
}
