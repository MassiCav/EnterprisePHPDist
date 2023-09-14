<?php declare(strict_types=1);

namespace DAL\Handler;

use DAL\Infrastructure\ProcessManager\ProcessPolicies;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;

class DomainEventsHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        if (!$container->has(ProcessPolicies::class)) 
            throw new ServiceNotFoundException("Could not find the 'ProcessPolicies' configuration in container!");
        $processor = $container->get(ProcessPolicies::class);
        return new DomainEventsHandler($processor);
    }
}
