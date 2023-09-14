<?php declare(strict_types=1);

namespace PM\Infrastructure\Policy;

use PM\EventSourcing\Domain\DomainEvent;
use PM\Commanding\Domain\Command;

trait PolicyInvokeImplementantionTrait
{
    /** psalm-return list<Command> **/
    public function __invoke(DomainEvent $event): array
    {
        $commands = [];
        /** @var Command $command */
        foreach ( $this->associatedCommands as $name => $command ) {
            $command = $command->setEvent($event);
            $commands[] = $command;
        }
        return $commands;
    }
}