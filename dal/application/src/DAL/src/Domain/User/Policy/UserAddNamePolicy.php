<?php declare(strict_types=1);

namespace DAL\Domain\User\Policy;

use DAL\Domain\User\Event\UserAddNameEvent;
use DAL\EventSourcing\Domain\DomainEvent;
use DAL\EventSourcing\Domain\Policy;
use DAL\Infrastructure\Policy\PolicyInvokeImplementantionTrait;

final class UserAddNamePolicy implements Policy 
{
    use PolicyInvokeImplementantionTrait;

    /** @psalm-param list<class-string<Command> $associatedCommands */
    public function __construct(public readonly array $associatedCommands)
    {}

    /** {@inheritDoc} */
    public function supportedDomainEvent() : string {
        return UserAddNameEvent::class;
    }
}