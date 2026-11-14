<?php

namespace App\Curriculum\Topic;

final readonly class QuestionDraft
{
    public function __construct(
        public string $text,
        public ?float $difficulty = null,
    ) {}
}
