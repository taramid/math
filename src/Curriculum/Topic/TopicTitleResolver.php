<?php

namespace App\Curriculum\Topic;

use App\Curriculum\Entity\Topic;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Attribute\AsTwigFunction;

final readonly class TopicTitleResolver
{
    public function __construct(private TranslatorInterface $translator) {}

    #[AsTwigFunction('topic_title')]
    public function title(Topic $topic): string
    {
        $key = $topic->getTranslationKey();

        return null !== $key
            ? $this->translator->trans("$key.title", [], 'topics')
            : (string) $topic->getTitle();
    }
}
