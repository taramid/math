<?php

declare(strict_types=1);

namespace App\Curriculum\Topic;

class Addition10Engine extends AbstractNativeTopicEngine
{
    public const string TOPIC_ID = '01a1d05e-d10c-7a3a-baed-bc292058bb10';
    public const string TRANSLATION_KEY = 'addition-10';

    public function questions(): iterable
    {
        return [];
    }
}
