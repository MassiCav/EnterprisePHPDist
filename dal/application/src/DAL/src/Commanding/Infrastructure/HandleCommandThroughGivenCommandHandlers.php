<?php declare(strict_types=1);

namespace DAL\Commanding\Infrastructure;

use DAL\Commanding\Domain\Command;
use DAL\Commanding\Infrastructure\Exception\CommandNotHandled;
use OpsWay\Doctrine\ORM\Swoole\EntityManager;

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
