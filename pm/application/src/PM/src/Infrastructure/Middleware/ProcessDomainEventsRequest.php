<?php declare(strict_types=1);

namespace PM\Infrastructure\Middleware;

use PM\Infrastructure\Serialization\DomainEventArrayDeserializer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware that ensures the requests object 
 * includes the necessary valid attributes in order
 * to trigger the policies processing
 */
class ProcessDomainEventsRequest implements MiddlewareInterface
{

    public function __construct(public readonly array $eventsTypeMap)
    {}

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        //TODO: Improve decoding error checking 
        $eventsArray = json_decode($request->getBody()->getContents(), true);
        $events = (new DomainEventArrayDeserializer)($eventsArray, $this->eventsTypeMap);
        $request = $request->withAttribute('events', $events);
        return $handler->handle($request);
    }
}
