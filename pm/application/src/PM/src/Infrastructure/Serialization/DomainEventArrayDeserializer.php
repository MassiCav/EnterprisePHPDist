<?php

declare(strict_types=1);

namespace PM\Infrastructure\Serialization;

use PM\EventSourcing\Domain\DomainEvent;
use PM\Infrastructure\Serialization\Exception\DomainEventDeserializationError;
use DateTimeImmutable;

/**
 * This class is supposed to be used as an entry-point, not as a dependency.
 *
 * Its purpose is to take event stream events, pass them through configured policies,
 * and forward resulting commands to the command bus.
 */
final class DomainEventArrayDeserializer
{
    /** 
     * @param list<class-string<DomainEvent>>
     * @return list<DomainEvent>
     */
    public function __invoke(array $events, array $eventsTypeMap): array
    {        
        $resolvedEvents = [];
        /** @var array $eventPayload */
        foreach ($events as $eventPayload) {
            $eventName = $eventPayload['metadata']['eventName'] ?? '';
            $targetClassName = $eventsTypeMap[$eventName] ?? '';
            if (empty($targetClassName)) throw DomainEventDeserializationError::fromMissingEventsTypeMapEntry($eventName, $eventsTypeMap);
            $resolvedEvents[] = $this->resolveDomainEvent($eventName, $eventPayload, $targetClassName);
        }
        return $resolvedEvents;
    }

    private function resolveDomainEvent(string $eventName, array $payload, string $targetClassName): DomainEvent
    {
        $domainEvent = (new \CuyZ\Valinor\MapperBuilder())
            ->supportDateFormats(DateTimeImmutable::RFC3339_EXTENDED)
            ->mapper()
            ->map(
                $targetClassName,
                \CuyZ\Valinor\Mapper\Source\Source::array($payload)
            );
        if (!$domainEvent instanceof DomainEvent)
            throw DomainEventDeserializationError::fromInvalidDomainEvent($eventName, $domainEvent);
        return $domainEvent;
    }
}
