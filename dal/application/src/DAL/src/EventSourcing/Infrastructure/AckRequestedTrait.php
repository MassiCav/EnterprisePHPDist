<?php declare(strict_types=1);

namespace DAL\EventSourcing\Infrastructure;

trait AckRequestedTrait
{
    public function ackRequested(): bool
    {
        return $this->metadata->ackRequested;
    }
}