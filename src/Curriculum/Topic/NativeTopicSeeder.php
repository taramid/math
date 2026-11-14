<?php

namespace App\Curriculum\Topic;

use App\Common\Contract\UpdateTaskInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

final readonly class NativeTopicSeeder implements UpdateTaskInterface
{
    public function __construct(
        #[AutowireIterator(NativeTopicEngineInterface::class)]
        private iterable $engines,
    ) {}

    public function update(): void
    {
        $engines = iterator_to_array($this->engines);

        usort(
            $engines,
            static fn (NativeTopicEngineInterface $a, NativeTopicEngineInterface $b) => $a::TOPIC_ID <=> $b::TOPIC_ID
        );

        foreach ($engines as $engine) {
            $engine->seed();
        }
    }
}
