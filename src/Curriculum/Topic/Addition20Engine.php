<?php

declare(strict_types=1);

namespace App\Curriculum\Topic;

class Addition20Engine extends AbstractNativeTopicEngine
{
    public const string TOPIC_ID = '01a1d08a-22e8-7382-9ef5-199e58c578c1';
    public const string TRANSLATION_KEY = 'addition-20';

    public function questions(): iterable
    {
        return [];
    }
}
