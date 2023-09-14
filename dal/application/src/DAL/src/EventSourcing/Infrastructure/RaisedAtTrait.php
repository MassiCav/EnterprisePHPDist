<?php declare(strict_types=1);

namespace DAL\EventSourcing\Infrastructure;

use DateTimeImmutable;

trait RaisedAtTrait
{
    public function raisedAt(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->metadata->createdAt);
    }
}