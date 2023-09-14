<?php declare(strict_types=1);

namespace DAL\Log;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Factory for the event handler
 */
class TaskStartLoggingListenerFactory
{
    /**
     * Creating the TaskFinishLoggingListener object
     *
     * @param ContainerInterface $container
     * @return TaskStartLoggingListener
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : TaskStartLoggingListener
    {
        $logger = $container->get(LoggerInterface::class);
        return new TaskStartLoggingListener($logger);
    }
}