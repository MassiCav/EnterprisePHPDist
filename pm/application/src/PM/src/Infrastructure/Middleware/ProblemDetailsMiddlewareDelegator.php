<?php declare(strict_types=1);

namespace PM\Infrastructure\Middleware;

use Mezzio\ProblemDetails\ProblemDetailsMiddleware as PDM;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class ProblemDetailsMiddlewareDelegator
{
    public function __invoke(ContainerInterface $container, $serviceName, callable $callback) : PDM
    {
        $middleware = $callback();
        $logger = $container->get(LoggerInterface::class);
        $middleware->attachListener(function($e, $request, $response) use ($logger) {
            $logger->critical($e->getMessage(), ['exception' => $e]);
        });
        return $middleware;
    }
}