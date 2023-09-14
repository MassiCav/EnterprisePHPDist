<?php declare(strict_types=1);

namespace PM\Log;

use Mezzio\Swoole\Event\TaskEvent;
use Monolog\Logger;


class TaskStartLoggingListener
{
    public function __construct(private readonly Logger $logger) {}

    public function __invoke(TaskEvent $event): void
    {
        $taskId = $event->getTaskId();
        $data = $event->getData();
        $this->logger->info(
            message: "Starting task id '$taskId'.",
            context: ['data' => $data]
        );
    }
}