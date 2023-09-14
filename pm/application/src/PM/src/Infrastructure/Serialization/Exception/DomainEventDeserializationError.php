<?php declare(strict_types=1);

namespace PM\Infrastructure\Serialization\Exception;

use PM\EventSourcing\Domain\DomainEvent;
use Psl\Json;
use Psl\Str;
use Psl\Vec;
use RuntimeException;

final class DomainEventDeserializationError extends RuntimeException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    /**  
     * @param list<class-string<DomainEvent>> $map 
     **/
    public static function fromMissingEventsTypeMapEntry(
        string $eventName,
        array $map,
    ): self {
        return new self(
            Str\format(
                "Could not find target class for event type \"%s\".\nEvents type map:\n%s",
                $eventName,
                Json\encode(
                    $map,
                    true,
                ),
            )
        );
    }

    /**  
     * @param list<class-string<DomainEvent>> $map 
     **/
    public static function fromInvalidDomainEvent(
        string $eventName,
        object $invalidEvent
    ): self {
        return new self(
            Str\format(
                "Could not make use of domain event '%s' for event type '%s'",
                \get_class($invalidEvent),
                $eventName
            )
        );
    }
}
