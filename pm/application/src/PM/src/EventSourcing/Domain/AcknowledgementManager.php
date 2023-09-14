<?php declare(strict_types=1);

namespace PM\EventSourcing\Domain;

use Throwable;

/** @psalm-immutable */
interface AcknowledgementManager
{
    public function deliverSuccess(DomainEvent $event): void;
    public function deliverFailure(DomainEvent $event, Throwable $error): void;
}
