<?php

declare(strict_types=1);

namespace App\Curriculum\Topic;

class Square30Engine extends AbstractNativeTopicEngine
{
    public const string TOPIC_ID = '01a1d047-0c27-7adb-8efb-fb9cb092d2e7';
    public const string TRANSLATION_KEY = 'squares-30';

    public function questions(): iterable
    {
        for ($a = 21; $a <= 30; ++$a) {

            yield new QuestionDraft("$a ^ 2");
        }
    }
}
