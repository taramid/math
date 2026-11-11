<?php

namespace App\Curriculum\Topic;

use Doctrine\ORM\EntityManagerInterface;

class MultiplicationEngine implements NativeTopicEngineInterface
{
    public const string TOPIC_ID = '01a1c105-e346-7092-81a4-a8618ed38d1a';
    public const string TRANSLATION_KEY = 'multiplication';

    public function __construct(private EntityManagerInterface $em) {}

    public static function getTopicId(): string
    {
        return self::TOPIC_ID;
    }

    public function seed(): void
    {

    }
}
