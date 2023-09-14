<?php declare(strict_types=1);

namespace PM\Infrastructure\Middleware;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;

class ProcessDomainEventsRequestFactory
{
    public function __invoke(ContainerInterface $container): ProcessDomainEventsRequest
    {
        $eventTypesMap = $container->get('config')['events_type_map'] ?? [];
        if (empty($eventTypesMap)) {
            throw new ServiceNotFoundException("Could not find the 'events_type_map' configuration in container!");
        }
        return new ProcessDomainEventsRequest($eventTypesMap);
    }
}
