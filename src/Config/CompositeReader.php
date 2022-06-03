<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Config;

use Magento\Framework\Config\ReaderInterface;
use Magento\Framework\ObjectManager\TMap;
use Magento\Framework\ObjectManager\TMapFactory;
use Magento\Framework\Stdlib\ArrayManager;

class CompositeReader implements ReaderInterface
{
    /* @var \Magento\Framework\ObjectManager\TMap|\Magento\Framework\Config\ReaderInterface[] */
    private TMap $readers;
    private ArrayManager $arrayManager;

    public function __construct(
        TMapFactory $TMapFactory,
        ArrayManager $arrayManager,
        array $readers = []
    )
    {
        $this->arrayManager = $arrayManager;
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
