<?php declare(strict_types=1);

namespace DAL;

use DAL\Domain\User\Query\UserQueryModel;
use DAL\Domain\User\Query\UserQueryModelFactory;
use DAL\EventSourcing\Domain\AcknowledgementManager;
use DAL\Infrastructure\Communication\AckOnRedisHashFactory;
use DAL\Infrastructure\Middleware\ProblemDetailsMiddlewareDelegator;
use DAL\Infrastructure\Middleware\ProcessDomainEventsRequest;
use DAL\Infrastructure\Middleware\ProcessDomainEventsRequestFactory;
use DAL\Infrastructure\Persistence\EMCLIFactory;
use DAL\Log\StdoutLoggerFactory;
use DAL\Log\TaskFinishLoggingListener;
use DAL\Log\TaskFinishLoggingListenerFactory;
use DAL\Log\TaskStartLoggingListener;
use DAL\Log\TaskStartLoggingListenerFactory;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\ConfigAggregator\ConfigAggregator;
use Psr\Log\LoggerInterface;
use DAL\Infrastructure\Persistence\EntityManagerFactory;

/**
 * The configuration provider for the DAL module
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
            Domain\User\ConfigProvider::class,
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
                Handler\PingHandler::class => Handler\PingHandler::class
            ],
            'factories'  => [
                LoggerInterface::class => StdoutLoggerFactory::class,
                ProcessDomainEventsRequest::class => ProcessDomainEventsRequestFactory::class,
                TaskFinishLoggingListener::class => TaskFinishLoggingListenerFactory::class,
                TaskStartLoggingListener::class => TaskStartLoggingListenerFactory::class,
                Handler\DomainEventsHandler::class => Handler\DomainEventsHandlerFactory::class,
                Handler\QueryUserHandler::class => Handler\QueryUserHandlerFactory::class,
                Infrastructure\ProcessManager\ProcessPolicies::class => Infrastructure\ProcessManager\ProcessPoliciesFactory::class,
                EntityManagerInterface::class => EntityManagerFactory::class,
                AcknowledgementManager::class => AckOnRedisHashFactory::class,
                UserQueryModel::class => UserQueryModelFactory::class,
                'EMCLI' => EMCLIFactory::class
            ],
            'delegators' => [
                \Mezzio\ProblemDetails\ProblemDetailsMiddleware::class => [
                    ProblemDetailsMiddlewareDelegator::class
                ]
            ],
        ];
    }
}
