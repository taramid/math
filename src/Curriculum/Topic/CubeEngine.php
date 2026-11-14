<?php

declare(strict_types=1);

namespace App\Curriculum\Topic;

class CubeEngine extends AbstractNativeTopicEngine
{
    public const string TOPIC_ID = '01a1d02a-d7ce-7732-99c3-ce66ae23fd28';
    public const string TRANSLATION_KEY = 'qubes';

    public function questions(): iterable
    {
        for ($a = 2; $a <= 9; ++$a) {

            yield new QuestionDraft("$a ^ 3");
        }
    }
}
