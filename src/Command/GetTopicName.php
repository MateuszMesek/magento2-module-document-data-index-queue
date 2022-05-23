<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Command;

class GetTopicName
{
    private string $namespace;

    public function __construct(
        string $namespace
    )
    {
        $this->namespace = $namespace;
    }

    public function execute(string $indexName): string
    {
        return $this->namespace.'.'.$indexName;
    }
}
