<?php declare(strict_types=1);

namespace PM\Commanding\Infrastructure;

use PM\Commanding\Domain\Command;

/** @template HandledCommand of Command */
interface CommandHandler
{
    /** @param HandledCommand $command */
    public function __invoke(Command $command): void;

    /** @return class-string<HandledCommand> */
    public function handlesCommand(): string;
}
