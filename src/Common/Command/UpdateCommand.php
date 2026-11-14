<?php

namespace App\Common\Command;

use App\Common\Contract\UpdateTaskInterface;
use App\Common\Event\UpdateTaskEvent;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsCommand(name: 'app:update', description: 'Initialize and keep it up to date')]
final readonly class UpdateCommand
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    public function __invoke(
        SymfonyStyle $io,
        #[AutowireIterator(UpdateTaskInterface::class)]
        iterable $tasks = [],
    ): int {

        $this->eventDispatcher->addListener(
            UpdateTaskEvent::class,
            function (UpdateTaskEvent $task) use ($io) {

                $shortName = new \ReflectionClass($task->taskName)->getShortName();

                $io->text(sprintf('%-44s %s', "<info>$shortName</info>", $task->message));
            }
        );

        foreach ($tasks as $task) {
            $task->update();
        }

        return Command::SUCCESS;
    }
}
