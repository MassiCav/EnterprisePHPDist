<?php declare(strict_types=1);

namespace DAL\Infrastructure\Communication;

use DAL\EventSourcing\Domain\AckErrorStatus;
use DAL\EventSourcing\Domain\AckMessage;
use DAL\EventSourcing\Domain\AcknowledgementManager;
use DAL\EventSourcing\Domain\DomainEvent;
use DAL\Infrastructure\Communication\Exception\AcknowledgementError;
use Swoole\Database\RedisPool;
use Throwable;


class AckOnRedisHash implements AcknowledgementManager
{

    public function __construct(public readonly RedisPool $pool)
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
