<?php

declare(strict_types=1);

namespace App\Curriculum\Topic;

final readonly class NativeQuestionDraft
{
    public function __construct(
        public string $text,
        public int $answer,
        public ?float $difficulty = null,
    ) {}
}
