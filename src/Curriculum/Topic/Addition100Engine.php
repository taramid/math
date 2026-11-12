<?php

declare(strict_types=1);

namespace App\Curriculum\Topic;

class Addition100Engine extends AbstractNativeTopicEngine
{
    public const string TOPIC_ID = '01a1d0a3-cc98-7848-a531-8cceb533d697';
    public const string TRANSLATION_KEY = 'addition-100';

    public function questions(): iterable
    {
        return [];
    }
}
