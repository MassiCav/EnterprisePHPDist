<?php declare(strict_types=1);

namespace DAL\Commanding\Infrastructure\Exception;

use DAL\Commanding\Domain\Command;
use DAL\Commanding\Infrastructure\CommandHandler;
use Psl\Json;
use Psl\Str;
use Psl\Vec;
use RuntimeException;

use function array_combine;
use function get_class;

final class CommandNotHandled extends RuntimeException
{
    private function __construct(string $message, public readonly Command $command)
    {
        parent::__construct($message);
    }

    /** @param list<CommandHandler> $commandHandlers */
    public static function fromCommandAndConfiguredCommandHandlers(
        Command $command,
        array $commandHandlers,
    ): self {
        return new self(
            Str\format(
                "Could not handle command of type \"%s\".\nConfigured handlers:\n%s",
                $command::class,
                Json\encode(
                    array_combine(
                        Vec\map($commandHandlers, static fn (CommandHandler $handler): string => $handler->handlesCommand()),
                        Vec\map($commandHandlers, get_class(...)),
                    ),
                    true,
                ),
            ),
            $command,
        );
    }
}
