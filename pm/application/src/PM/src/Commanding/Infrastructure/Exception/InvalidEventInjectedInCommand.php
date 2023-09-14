<?php declare(strict_types=1);

namespace PM\Commanding\Infrastructure\Exception;

use PM\Commanding\Domain\Command;
use PM\EventSourcing\Domain\DomainEvent;
use Psl\Str;
use RuntimeException;

final class InvalidEventInjectedInCommand extends RuntimeException
{
    private function __construct(string $message, public readonly Command $command)
    {
        parent::__construct($message);
    }

    /**
     * Create exception
     *
     * @param DomainEvent $event
     * @param Command $command
     * @return self
     */
    public static function fromIjectedEvenAndCommand(
        DomainEvent $event,
        Command $command,
    ): self {
        return new self(
            Str\format(
                "Invalid events '%s' injected in command of type '%s'!",
                $event::class,
                $command::class
            ),
            $command
        );
    }
}
