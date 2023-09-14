<?php declare(strict_types=1);

namespace PM\Infrastructure\Communication;

use OpenSwoole\Core\Coroutine\Pool\ClientPool;
use PM\EventSourcing\Domain\AckErrorStatus;
use PM\EventSourcing\Domain\AckMessage;
use PM\EventSourcing\Domain\AcknowledgementManager;
use PM\EventSourcing\Domain\DomainEvent;
use PM\Infrastructure\Communication\Exception\AcknowledgementError;
use Throwable;


class AckOnRedisHash implements AcknowledgementManager
{

    public function __construct(public readonly ClientPool $pool)
    {}

    /**
     * {@inheritDoc}
     */
    public function deliverSuccess(DomainEvent $event): void
    {
        if ($event->ackRequested()) {
            $ack = new AckMessage(AckErrorStatus::Success->value, '', 200);
            $channel = $event->metadata->ackChannel;
            $res = $this->deliver($ack, $channel);
            if (!$res) {
                throw AcknowledgementError::fromRedisHash($event, $channel);
            }
        }
    }

    public function deliverFailure(DomainEvent $event, Throwable $error): void
    {
        if ($event->ackRequested()) {
            $ack = new AckMessage(AckErrorStatus::Failure->value, $error->getMessage(), $error->getCode() ?? 500);
            $channel = $event->metadata->ackChannel;
            $res = $this->deliver($ack, $channel);
            if (!$res) {
                throw AcknowledgementError::fromRedisHash($event, $channel);
            }
        }
    }

    private function deliver(AckMessage $ack, string $channel): bool
    {
        $redis = $this->pool->get();
        $res = $redis->hMSet($channel, $ack->toArray());
        $redis->expire($channel, 5);
        $this->pool->put($redis);
        return $res;
    }
}
