<?php

namespace App\Common\Event;

final readonly class UpdateTaskEvent
{
    public function __construct(
        public string $taskName,
        public string $message,
    ) {}
}
