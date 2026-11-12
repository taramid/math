<?php

declare(strict_types=1);

namespace App\Curriculum\Topic;

use App\Curriculum\Entity\Topic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class Addition20Engine implements NativeTopicEngineInterface
{
    public const string TOPIC_ID = '01a1d08a-22e8-7382-9ef5-199e58c578c1';

    public const string TRANSLATION_KEY = 'addition-20';

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
            $this->em->flush();
        });
    }
}
