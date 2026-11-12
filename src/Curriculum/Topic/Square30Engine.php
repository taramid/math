<?php

declare(strict_types=1);

namespace App\Curriculum\Topic;

use App\Curriculum\Entity\Question;
use App\Curriculum\Entity\Topic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class Square30Engine implements NativeTopicEngineInterface
{
    public const string TOPIC_ID = '01a1d047-0c27-7adb-8efb-fb9cb092d2e7';

    public const string TRANSLATION_KEY = 'squares-30';

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

            for ($a = 21; $a <= 30; ++$a) {

                $this->em->persist(new Question($topic, "$a ^ 2"));
            }

            $this->em->flush();
        });
    }
}
