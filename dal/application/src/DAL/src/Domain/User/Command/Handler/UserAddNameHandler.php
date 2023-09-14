<?php declare(strict_types=1);

namespace DAL\Domain\User\Command\Handler;

use DAL\Commanding\Infrastructure\CommandHandler;
use DAL\Commanding\Infrastructure\CommandHandlerExecuteTrait;
use DAL\Domain\User\Command\Command\UserAddName;

final class UserAddNameHandler implements CommandHandler 
{
    use CommandHandlerExecuteTrait; 

    /** @return class-string<HandledCommand> */
    public function handlesCommand(): string
    {
        return UserAddName::class;
    }
}