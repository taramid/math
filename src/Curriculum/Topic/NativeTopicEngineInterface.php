<?php

namespace App\Curriculum\Topic;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface NativeTopicEngineInterface extends TopicEngineInterface
{
    public static function getTopicId(): string;

    public function seed(): void;
}
