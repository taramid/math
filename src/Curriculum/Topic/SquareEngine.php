<?php

declare(strict_types=1);

namespace App\Curriculum\Topic;

use App\Curriculum\Entity\Question;
use App\Curriculum\Entity\Topic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class SquareEngine implements NativeTopicEngineInterface
{
    public const string TOPIC_ID = '01a1d014-be9d-7a4b-82e1-308179c0394f';

    public const string TRANSLATION_KEY = 'squares';

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

            for ($a = 11; $a <= 20; ++$a) {

                $this->em->persist(new Question($topic, "$a ^ 2"));
            }

            $this->em->flush();
        });
    }
}
