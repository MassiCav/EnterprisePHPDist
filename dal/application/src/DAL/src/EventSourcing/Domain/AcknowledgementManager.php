<?php declare(strict_types=1);

namespace DAL\EventSourcing\Domain;

use Throwable;

/** @psalm-immutable */
interface AcknowledgementManager
{
    public function deliverSuccess(DomainEvent $event): void;
    public function deliverFailure(DomainEvent $event, Throwable $error): void;
}
