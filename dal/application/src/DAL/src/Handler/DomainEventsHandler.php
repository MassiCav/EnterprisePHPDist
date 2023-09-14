<?php declare(strict_types=1);

namespace DAL\Handler;

use DAL\Infrastructure\ProcessManager\ProcessPolicies;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use InvalidArgumentException;

class DomainEventsHandler implements RequestHandlerInterface
{
    public function __construct(public readonly ProcessPolicies $processor)
    {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $receivedEvents = $request->getAttribute('events', []);
        if (empty($receivedEvents) || !is_array($receivedEvents)) {
            throw new InvalidArgumentException("No valid events received in 'DomainEventsHandler' controller!");
        }
        $this->processor->__invoke($receivedEvents);
        return new EmptyResponse(202);
    }
}
