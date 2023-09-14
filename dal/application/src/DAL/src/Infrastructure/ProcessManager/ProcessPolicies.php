<?php declare(strict_types=1);

namespace DAL\Infrastructure\ProcessManager;

use DAL\Commanding\Infrastructure\CommandBus;
use DAL\EventSourcing\Domain\AcknowledgementManager;
use DAL\EventSourcing\Domain\DomainEvent;
use DAL\EventSourcing\Domain\Policy;
use DAL\EventSourcing\Domain\AckMessage;
use Throwable;

use function array_walk;

/**
 * This class is supposed to be used as an entry-point, not as a dependency.
 *
 * Its purpose is to take event stream events, pass them through configured policies,
 * and forward resulting commands to the command bus.
 */
final class ProcessPolicies
{
    /** @param list<Policy> $policies */
    public function __construct(
        private readonly array $policies,
        private readonly CommandBus $commandBus,
        private readonly AcknowledgementManager $ackMng
    ) {
    }

     /** @param list<DomainEvent> $policies */
    public function __invoke(array $events): void
    {
        foreach ($events as $event) {
            $this->handleEvent($event);
        }
    }

    private function handleEvent(DomainEvent $event): void
    {
        /** @var $policy Policy */
        foreach ($this->policies as $policy) {
            try {
                $handledEventType = $policy->supportedDomainEvent();
                if (! $event instanceof $handledEventType) {
                    continue;
                }
                $commands = $policy($event);
                array_walk($commands, $this->commandBus);
                $this->ackMng->deliverSuccess($event);
            } catch (Throwable $t) {
                $this->ackMng->deliverFailure($event, $t);
                throw $t;
            } 
        }
    }
}
