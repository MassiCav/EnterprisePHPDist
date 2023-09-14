<?php declare(strict_types=1);

namespace DAL;

use PM\EventSourcing\Domain\AcknowledgementManager;
use PM\Infrastructure\Communication\AckOnRedisHashFactory;
use PM\Infrastructure\Communication\NoActualAck;
use PM\Infrastructure\Middleware\ProblemDetailsMiddlewareDelegator;
use PM\Log\StdoutLoggerFactory;
use PM\Log\TaskFinishLoggingListener;
use PM\Log\TaskFinishLoggingListenerFactory;
use PM\Log\TaskStartLoggingListener;
use PM\Log\TaskStartLoggingListenerFactory;
use Laminas\ConfigAggregator\ConfigAggregator;
use Psr\Log\LoggerInterface;

/**
 * The configuration provider for the Policies Manager module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return (new ConfigAggregator([
            function (): array {
                return [
                    'dependencies' => $this->getDependencies(),
                ];
            },
        ]))->getMergedConfig();
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
                Handler\PingHandler::class => Handler\PingHandler::class,
                AcknowledgementManager::class => NoActualAck::class
            ],
            'factories' => [
                LoggerInterface::class => StdoutLoggerFactory::class,
                TaskFinishLoggingListener::class => TaskFinishLoggingListenerFactory::class,
                TaskStartLoggingListener::class => TaskStartLoggingListenerFactory::class,
                Infrastructure\ProcessManager\ProcessPolicies::class => Infrastructure\ProcessManager\ProcessPoliciesFactory::class
            ],
            'delegators' => [
                \Mezzio\ProblemDetails\ProblemDetailsMiddleware::class => [
                    ProblemDetailsMiddlewareDelegator::class
                ]
            ],
        ];
    }
}