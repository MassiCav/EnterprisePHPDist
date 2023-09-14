<?php declare(strict_types=1);

namespace PM\Commanding\Infrastructure;

use PM\Commanding\Domain\Command;
use PM\Commanding\Infrastructure\Exception\CommandNotHandled;

interface CommandBus
{
    /** @throws CommandNotHandled */
    public function __invoke(Command $command): void;
}
