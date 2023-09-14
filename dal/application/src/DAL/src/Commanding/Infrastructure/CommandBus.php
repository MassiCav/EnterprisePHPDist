<?php declare(strict_types=1);

namespace DAL\Commanding\Infrastructure;

use DAL\Commanding\Domain\Command;
use DAL\Commanding\Infrastructure\Exception\CommandNotHandled;

interface CommandBus
{
    /** @throws CommandNotHandled */
    public function __invoke(Command $command): void;
}
