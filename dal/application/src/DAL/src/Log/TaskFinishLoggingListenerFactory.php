<?php declare(strict_types=1);

namespace DAL\Log;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Factory for the event handler
 */
class TaskFinishLoggingListenerFactory
{
    /**
     * Creating the TaskFinishLoggingListener object
     *
     * @param ContainerInterface $container
     * @return TaskFinishLoggingListener
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : TaskFinishLoggingListener
    {
        $logger = $container->get(LoggerInterface::class);
        return new TaskFinishLoggingListener($logger);
    }
}