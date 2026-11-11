<?php

declare(strict_types=1);

namespace App\Curriculum\Topic;

use App\Curriculum\Entity\Question;
use App\Curriculum\Entity\Topic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class MultiplicationEngine implements NativeTopicEngineInterface
{
    public const string TOPIC_ID = '01a1c105-e346-7092-81a4-a8618ed38d1a';
    public const string TRANSLATION_KEY = 'multiplication';

    public function __construct(private readonly EntityManagerInterface $em) {}

    public static function getTopicId(): string
    {
        return self::TOPIC_ID;
    }

    public function seed(): void
    {
        $id = Uuid::fromString(self::TOPIC_ID);

        if ($this->em->find(Topic::class, $id)) {
            return;
        }

        $this->em->wrapInTransaction(function () use ($id) {
            $topic = new Topic($id);
            $topic->setTranslationKey(self::TRANSLATION_KEY);
            $this->em->persist($topic);

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

                    $this->em->persist(new Question($topic, "$a * $b", $difficulty));
                }
            }

            $this->em->flush();
        });
    }
}
