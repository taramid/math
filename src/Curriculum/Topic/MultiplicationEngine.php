<?php

declare(strict_types=1);

namespace App\Curriculum\Topic;

class MultiplicationEngine extends AbstractNativeTopicEngine
{
    public const string TOPIC_ID = '01a1c105-e346-7092-81a4-a8618ed38d1a';
    public const string TRANSLATION_KEY = 'multiplication';


    protected function questions(): iterable
    {
        for ($a = 2; $a <= 9; ++$a) {
            for ($b = 2; $b <= 9; ++$b) {

                $difficulty = 1.0;

                if ($a > $b) {
                    $difficulty *= 1.08;
                }

                if ($a === $b) {
                    $difficulty *= .8;
                }

                foreach ([$a, $b] as $n) {
                    $difficulty *= match ($n) {
                        7 => 1.25,
                        8 => 1.22,
                        9 => 1.2,
                        6 => 1.1,
                        4 => 1.0,
                        3 => 0.92,
                        5 => 0.9,
                        2 => 0.75,
                    };
                    // https://numdrill.com/blog/hardest-times-tables
                }

                yield new QuestionDraft("$a * $b", $difficulty);
            }
        }
    }
}
