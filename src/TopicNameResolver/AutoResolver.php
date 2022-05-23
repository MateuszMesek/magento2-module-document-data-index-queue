<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\TopicNameResolver;

use MateuszMesek\DocumentDataIndexIndexerApi\DimensionResolverInterface;
use MateuszMesek\DocumentDataIndexQueueApi\TopicNameResolverInterface;

class AutoResolver implements TopicNameResolverInterface
{
    private DimensionResolverInterface $documentNameResolver;
    private DimensionResolverInterface $storeIdResolver;

    public function __construct(
        DimensionResolverInterface $documentNameResolver,
        DimensionResolverInterface $storeIdResolver
    )
    {
        $this->documentNameResolver = $documentNameResolver;
        $this->storeIdResolver = $storeIdResolver;
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
