<?php

namespace App\Curriculum\Topic;

class MultiplicationEngine implements NativeTopicEngineInterface
{
    public const string TOPIC_ID = '01a1c105-e346-7092-81a4-a8618ed38d1a';

    public static function getTopicId(): string
    {
        return self::TOPIC_ID;
    }

    public function seed(): void
    {

    }
}
