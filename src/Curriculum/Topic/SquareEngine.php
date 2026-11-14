<?php

declare(strict_types=1);

namespace App\Curriculum\Topic;

class SquareEngine extends AbstractNativeTopicEngine
{
    public const string TOPIC_ID = '01a1d014-be9d-7a4b-82e1-308179c0394f';
    public const string TRANSLATION_KEY = 'squares';

    public function questions(): iterable
    {
        for ($a = 11; $a <= 20; ++$a) {

            yield new QuestionDraft("$a ^ 2");
        }
    }
}
