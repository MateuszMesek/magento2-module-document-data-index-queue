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
