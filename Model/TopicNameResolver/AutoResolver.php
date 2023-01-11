<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Model\TopicNameResolver;

use MateuszMesek\DocumentDataIndexIndexerApi\Model\DimensionResolverInterface;
use MateuszMesek\DocumentDataIndexQueueApi\Model\TopicNameResolverInterface;

class AutoResolver implements TopicNameResolverInterface
{
    public function __construct(
        private readonly DimensionResolverInterface $documentNameResolver,
        private readonly DimensionResolverInterface $storeIdResolver
    )
    {
    }

    public function resolve(array $dimensions): string
    {
        $parts = [
            $this->documentNameResolver->resolve($dimensions)
        ];

        $storeId = $this->storeIdResolver->resolve($dimensions);

        if (null !== $storeId) {
            $parts[] = $storeId;
        }

        return implode('.', $parts);
    }
}
