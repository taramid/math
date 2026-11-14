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
        foreach ($this->engines as $engine) {
            $engine->seed();
        }
    }
}
