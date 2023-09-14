<?php declare(strict_types=1);

namespace PM\EventSourcing\Infrastructure;

use DateTimeImmutable;

trait RaisedAtTrait
{
    public function raisedAt(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->metadata->raisedAt);
    }
}