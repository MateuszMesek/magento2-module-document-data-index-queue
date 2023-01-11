<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexQueue\Model\Command;

class GetTopicName
{
    public function __construct(
        private readonly string $namespace
    )
    {
    }

    public function execute(string $topicName): string
    {
        return $this->namespace . '.' . $topicName;
    }
}
