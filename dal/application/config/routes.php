<?php

declare(strict_types=1);

use DAL\Handler\DomainEventsHandler;
use DAL\Infrastructure\Middleware\ProcessDomainEventsRequest;
use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

/**
 * FastRoute route configuration
 *
 * @see https://github.com/nikic/FastRoute
 */
return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->post(
        '/deh', 
        [ 
            ProcessDomainEventsRequest::class,
            DomainEventsHandler::class
        ], 
        'domain.events.handler'
    );
    $app->get('/user[/{id}]', DAL\Handler\QueryUserHandler::class, 'query.user');
    $app->get('/ping', DAL\Handler\PingHandler::class, 'ping');
};
