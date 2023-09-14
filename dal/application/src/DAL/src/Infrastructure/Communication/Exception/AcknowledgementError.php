<?php declare(strict_types=1);

namespace DAL\Infrastructure\Communication\Exception;

use DAL\EventSourcing\Domain\AckMessage;
use DAL\EventSourcing\Domain\DomainEvent;
use Psl\Str;
use RuntimeException;

use get_class;

final class AcknowledgementError extends RuntimeException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    /**  
     * 
     **/
    public static function fromRedisHash(
        DomainEvent $event,
        string $channel
    ): self {
        $eventName = get_class($event);
        return new self(
            Str\format(
                "Could not acknowledge event '%s' on Redis channel '%s' using 'hMset'!",
                $eventName,
                $channel,
            )
        );
    }
}
