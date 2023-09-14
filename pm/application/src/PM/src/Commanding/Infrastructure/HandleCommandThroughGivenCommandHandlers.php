<?php declare(strict_types=1);

namespace PM\Commanding\Infrastructure;

use PM\Commanding\Domain\Command;
use PM\Commanding\Infrastructure\Exception\CommandNotHandled;

final class HandleCommandThroughGivenCommandHandlers implements CommandBus
{
    /**
     * @param array $handlers
     *
     * @template CommandType of Command
     */
    public function __construct(private readonly array $handlers)
    {}

    public function __invoke(Command $command): void
    {
        foreach ($this->handlers as $handler) {
            $handledCommand = $handler->handlesCommand();
            if ($command instanceof $handledCommand) {
                $handler($command);
                return;
            }
        }

        throw CommandNotHandled::fromCommandAndConfiguredCommandHandlers($command, $this->handlers);
    }
}
