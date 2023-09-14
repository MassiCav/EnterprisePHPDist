<?php declare(strict_types=1);

namespace PM\EventSourcing\Infrastructure;

trait AckRequestedTrait
{
    public function ackRequested(): bool
    {
        return $this->metadata->ackRequested;
    }
}