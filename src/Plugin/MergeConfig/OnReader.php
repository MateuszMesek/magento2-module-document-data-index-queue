<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Plugin\MergeConfig;

use Magento\Framework\Config\ReaderInterface;

class OnReader
{
    private ReaderInterface $reader;

    public function __construct(
        ReaderInterface $reader
    )
    {
        $this->reader = $reader;
    }

    public function afterRead(
        ReaderInterface $reader,
        array $result,
        $scope = null
    )
    {
        return array_merge_recursive(
            $result,
            $this->reader->read($scope)
        );
    }
}
