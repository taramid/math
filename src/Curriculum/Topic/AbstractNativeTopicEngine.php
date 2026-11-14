<?php

declare(strict_types=1);

namespace App\Curriculum\Topic;

use App\Common\Event\UpdateTaskEvent;
use App\Curriculum\Entity\Question;
use App\Curriculum\Entity\Topic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


abstract class AbstractNativeTopicEngine implements NativeTopicEngineInterface
{
    public const string TOPIC_ID = '';
    public const string TRANSLATION_KEY = '';

    public function __construct(
        protected readonly EntityManagerInterface $em,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {}


    /** @return iterable<QuestionDraft> */
    abstract protected function questions(): iterable;


    public static function getNativeTopicId(): string
    {
        return static::TOPIC_ID;
    }


    public static function getTranslationKey(): string
    {
        return static::TRANSLATION_KEY;
    }


    public function seed(): void
    {
        if ('' === static::TOPIC_ID || '' === static::TRANSLATION_KEY) {
            throw new \LogicException(static::class.' must define TOPIC_ID and TRANSLATION_KEY.');
        }

        $id = Uuid::fromString(static::getNativeTopicId());

        if ($this->em->find(Topic::class, $id)) {
            return;
        }

        $this->em->wrapInTransaction(function () use ($id) {
            $topic = new Topic($id);
            $topic->setTranslationKey(static::getTranslationKey());
            $this->em->persist($topic);

            $questions = iterator_to_array($this->questions());
            $count = count($questions);

            foreach ($questions as $q) {
                $this->em->persist(new Question($topic, $q->text, $q->difficulty));
            }

            $this->em->flush();

            $this->eventDispatcher->dispatch(
                new UpdateTaskEvent(static::class, 0 < $count ? "$count questions" : '')
            );
        });
    }
}
