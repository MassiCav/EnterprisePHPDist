<?php declare(strict_types=1);

namespace DAL\Infrastructure\ProcessManager;

use DAL\Commanding\Infrastructure\CommandHandler;
use DAL\Commanding\Infrastructure\HandleCommandThroughGivenCommandHandlers;
use DAL\EventSourcing\Domain\AcknowledgementManager;
use DAL\EventSourcing\Domain\Policy;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Mezzio\Exception\MissingDependencyException;
use Throwable;

use function Swoole\Coroutine\batch;

class ProcessPoliciesFactory
{
    public function __invoke(ContainerInterface $container): ProcessPolicies
    {
        $policiesConfig = $container->get('config')['policies'] ?? [];
        if (empty($policiesConfig)) {
            throw new ServiceNotFoundException("Could not find the 'policies' configuration in container!");
        }
        $handlersConfig = $container->get('config')['handlers'] ?? [];
        if (empty($handlersConfig)) {
            throw new ServiceNotFoundException("Could not find the 'command handlers' configuration in container!");
        }
        $tasks = [
            'policies' =>  function () use ($container, $policiesConfig) {
                $policies = [];
                try {
                    foreach ($policiesConfig as $policyClassName => $associateCommands) {
                        $policy = $container->get($policyClassName);
                        if ($policy instanceof Policy) {
                            $policies[] = $policy;
                            continue;
                        }
                        throw new InvalidServiceException("Policy '{$policyClassName}' is not a valid 'Policy' interface!");
                    }
                    return $policies;
                } catch (Throwable $error) {
                    return $error;
                }
            },
            'handlers' =>  function () use ($container, $handlersConfig) {
                $handlers = [];
                try {
                    foreach ($handlersConfig as $handlerClassName) {
                        $handler = $container->get($handlerClassName);
                        if ($handler instanceof CommandHandler) {
                            $handlers[] = $handler;
                            continue;
                        }
                        throw new InvalidServiceException("Command handler '{$handlerClassName}' is not a valid 'CommandHandler' interface!");
                    }
                    return $handlers;
                } catch (Throwable $error) {
                    return $error;
                }
            }
        ];
        $result = batch($tasks);
        $policies = $result['policies'];
        $handlers = $result['handlers'];
        $this->checkCoroutineResults($policies, $handlers);
        $commandBus = new HandleCommandThroughGivenCommandHandlers($handlers);
        $ackMgr = $container->get(AcknowledgementManager::class);
        return new ProcessPolicies($policies, $commandBus, $ackMgr);
    }

    /**
     * Validate coroutines results
     *
     * @param array|Throwable $policies
     * @param array|Throwable $handlers
     * @return void
     * @throws ServiceNotFoundException|MissingDependencyException
     */
    private function checkCoroutineResults(array|Throwable $policies, array|Throwable $handlers): void
    {
        if (empty($policies)) throw new ServiceNotFoundException("Could not find the valid 'policies' classes in container configurations!");
        if ($policies instanceof Throwable) throw new MissingDependencyException($policies->getMessage(), 500, $policies);
        if (empty($handlers)) throw new ServiceNotFoundException("Could not find the valid 'command handlers' classes in container configurations!");
        if ($handlers instanceof Throwable) throw new MissingDependencyException($handlers->getMessage(), 500, $handlers);
        return;
    }
}
