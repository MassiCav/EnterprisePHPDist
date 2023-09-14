<?php declare(strict_types=1);

namespace DAL\Domain\User\Event;

use DAL\EventSourcing\Domain\DomainEvent;
use DAL\EventSourcing\Domain\Metadata;
use DAL\EventSourcing\Infrastructure\AckRequestedTrait;
use DAL\EventSourcing\Infrastructure\RaisedAtTrait;

final class UserAddNameEventData
{
    public function __construct(public readonly string $id, public readonly string $name)
    {}
}

final class UserAddNameEvent implements DomainEvent 
{ 
    use RaisedAtTrait;
    use AckRequestedTrait;

    public function __construct(
        public readonly UserAddNameEventData $data,
        public readonly Metadata $metadata
    ){}

    /**
     * Note: for the purposes of this demo, it is endorsed that these array keys correspond
     *       to constructor parameter names. If that's not the case, there will be fun/trouble :-D
     *       That's because event de-serialization is currently based off simplistic assumptions,
     *       which may be fixed with a more elaborate serializer.
     *
     * @return array<non-empty-string, string|int|bool|float|array|null>
     */
    public function toArray(): array
    {
        return [ 
            'data' => [
                'id' => $this->data->id,
                'name' => $this->data->name,
            ],
            'metadata' => $this->metadata->toArray() 
        ];
    }
}