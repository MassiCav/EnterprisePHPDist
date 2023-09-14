<?php declare(strict_types=1);

namespace PM\Commanding\Domain;

use PM\EventSourcing\Domain\DomainEvent;

/**
 * A command is an immutable payload that expresses the intent of performing an operation on the system.
 * Its API can vary greatly from implementation to implementation, but it must be immutable.
 *
 * @psalm-immutable
 */
interface Command
{
    /**
     * Injectable event to manage
     *
     * @param DomainEvent $e
     * @return Command
     */
    public function setEvent(DomainEvent $e): Command;
    /**
     * Execute domain logic
     */
    public function execute(): void;
}
