<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Plugin\MergeConfig;

use Magento\Framework\Config\ReaderInterface;

class OnReader
{
    public function __construct(
        private readonly ReaderInterface $reader
    )
    {
    }

    public function afterRead(
        ReaderInterface $reader,
        array           $result,
                        $scope = null
    )
    {
        $results = [
            $this->reader->read($scope),
            $result
        ];

        return array_replace_recursive(
            ...$results
        );
    }
}
