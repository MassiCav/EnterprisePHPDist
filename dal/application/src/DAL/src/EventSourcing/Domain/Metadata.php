<?php declare(strict_types=1);

namespace DAL\EventSourcing\Domain;

use DateTimeInterface;

/**
 * @psalm-immutable
 */
final class UserMetadata 
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $group
    ) {}

    public function toArray(): array  {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'group' => $this->group
        ];
    }
}

/**
 * @psalm-immutable
 *
 * Metadata must have a specific schema for all events.
 */
final class  Metadata
{

    public function __construct(
        public readonly string $eventId,
        public readonly string $stream,
        public readonly string $eventName,
        public readonly string $ackChannel,
        public readonly string $graph,
        public readonly bool $ackRequested,
        public readonly UserMetadata $user,
        public readonly DateTimeInterface $createdAt
    ) {}

    /** @psalm-return array */
    public function toArray(): array 
    {
        return [
            'eventId' => $this->eventId,         
            'eventName' => $this->eventName,         
            'stream' => $this->stream,         
            'ackChannel' => $this->ackChannel,
            'user' => $this->user->toArray(),
            'createdAt' => $this->createdAt         
        ];
    }
}
