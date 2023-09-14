<?php declare(strict_types=1);

namespace DAL\Commanding\Infrastructure;

use DAL\Commanding\Domain\Command;

trait CommandHandlerExecuteTrait
{
    public function __invoke(Command $command): void
    {
        $command->execute();
    }
}