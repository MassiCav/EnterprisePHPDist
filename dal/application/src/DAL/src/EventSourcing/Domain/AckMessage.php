<?php declare(strict_types=1);

namespace DAL\EventSourcing\Domain;

enum AckErrorStatus : string {
    case Success = '0';
    case Failure = '1';
}

/**
 * @psalm-immutable
 */
final class AckMessage 
{
    public function __construct(
        public readonly string $error,
        public readonly string $message,
        public readonly int $code
    ) {}

    public function toArray(): array  {
        return [
            'error' => $this->error,
            'message' => $this->message,
            'code' => $this->code
        ];
    }
}