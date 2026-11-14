<?php

namespace App\Common\Command;

use App\Common\Contract\UpdateTaskInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

#[AsCommand(name: 'app:update', description: 'Initialize and keep it up to date')]
final readonly class UpdateCommand
{
    public function __invoke(
        #[AutowireIterator(UpdateTaskInterface::class)]
        iterable $tasks = [],
    ): int {

        foreach ($tasks as $task) {
            $task->update();
        }

        return Command::SUCCESS;
    }
}
