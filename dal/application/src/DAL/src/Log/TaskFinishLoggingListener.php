<?php declare(strict_types=1);

namespace DAL\Log;

use Mezzio\Swoole\Event\TaskFinishEvent;
use Monolog\Logger;


class TaskFinishLoggingListener
{
    public function __construct(private readonly Logger $logger) {}

    public function __invoke(TaskFinishEvent $event): void
    {
        $taskId = $event->getTaskId();
        $data = $event->getData();
        $this->logger->info(
            message: "Completed task id '$taskId'.",
            context: ['data' => $data]
        );
    }
}