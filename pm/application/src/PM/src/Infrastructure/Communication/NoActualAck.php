<?php declare(strict_types=1);

namespace PM\Infrastructure\Communication;

use PM\EventSourcing\Domain\AcknowledgementManager;
use PM\EventSourcing\Domain\DomainEvent;
use Throwable;


class NoActualAck implements AcknowledgementManager
{
    /**
     * {@inheritDoc}
     */
    public function deliverSuccess(DomainEvent $event): void
    {}

    public function deliverFailure(DomainEvent $event, Throwable $error): void
    {}
}
