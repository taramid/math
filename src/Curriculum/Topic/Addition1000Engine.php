<?php

declare(strict_types=1);

namespace App\Curriculum\Topic;

class Addition1000Engine extends AbstractNativeTopicEngine
{
    public const string TOPIC_ID = '01a1d0a5-00e0-74e2-b554-63f82214361c';
    public const string TRANSLATION_KEY = 'addition-1000';

    public function questions(): iterable
    {
        return [];
    }
}
