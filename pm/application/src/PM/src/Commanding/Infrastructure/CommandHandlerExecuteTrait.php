<?php declare(strict_types=1);

namespace PM\Commanding\Infrastructure;

use PM\Commanding\Domain\Command;

trait CommandHandlerExecuteTrait
{
    public function __invoke(Command $command): void
    {
        $command->execute();
    }
}