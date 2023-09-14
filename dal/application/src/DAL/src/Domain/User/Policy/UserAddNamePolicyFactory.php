<?php declare(strict_types=1);

namespace DAL\Domain\User\Policy;

use DAL\Commanding\Domain\Command;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;

use Throwable;

use function Swoole\Coroutine\batch;

class UserAddNamePolicyFactory
{
    public function __invoke(ContainerInterface $container): UserAddNamePolicy
    {
        $policy = UserAddNamePolicy::class;
        $associatedCommands = $container->get('config')['policies'][$policy] ?? [];
        if (empty($associatedCommands)) {
            throw new ServiceNotFoundException("Could not find the commands associated to {$policy} related configuration in container!");
        }
        $tasks = [];
        foreach ($associatedCommands as $commandName) {
            $tasks[] = function() use ($container, $commandName) {
                try {
                    $command = $container->get($commandName);
                    if ($command instanceof Command) return $command;
                    throw new InvalidServiceException("Retrieve command {$command} does not support inherent interface!");
                } catch (Throwable $t) {
                    return $t;
                }
            };
        }
        $commands = batch($tasks);
        $this->checkCoroutineResults($commands);
        return new $policy($commands);
    }

    /**
     * Validate coroutines results
     *
     * @param array $coroutinesResults
     * @return void
     * @throws ServiceNotFoundException
     */
    private function checkCoroutineResults(array $coroutinesResults): void
    {
        foreach ($coroutinesResults as $commandName => $res) {
            if ($res instanceof Throwable) {
                $originalMessage = $res->getMessage();
                $originalCode = $res->getCode() ?? 500;
                throw new InvalidServiceException(
                    "The creation of command {$commandName} failed with error: {$originalMessage}",
                    intval($originalCode)
                );
            }
        }
        return;
    }
}
