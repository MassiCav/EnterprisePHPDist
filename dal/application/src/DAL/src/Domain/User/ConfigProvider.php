<?php declare(strict_types=1);

namespace DAL\Domain\User;

use DAL\Domain\User\Command\Handler\UserAddNameHandler;
use DAL\Domain\User\Command\Command\UserAddName;
use DAL\Domain\User\Command\Command\UserAddNameFactory;
use DAL\Domain\User\Event\UserAddNameEvent;
use DAL\Domain\User\Policy\UserAddNamePolicy;
use DAL\Domain\User\Policy\UserAddNamePolicyFactory;

/**
 * The configuration provider for the User domain module
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
        return [
            'dependencies' => $this->getDependencies(),
            'handlers' => $this->getCommandHandlers(),
            'policies' => $this->getPolicies(),
            'events_type_map' => $this->getEventsTypeMap() 
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
                UserAddNameHandler::class => UserAddNameHandler::class
            ],
            'factories' => [
                UserAddNamePolicy::class => UserAddNamePolicyFactory::class,
                UserAddName::class => UserAddNameFactory::class
            ]
        ];
    }

    /**
     * Returns the command handlers list
     */
    public function getCommandHandlers(): array
    {
        return [
            UserAddNameHandler::class
        ];
    }

    /**
     * Returns the policies list with the associated command names
     */
    public function getPolicies(): array
    {
        return [
            UserAddNamePolicy::class => [ UserAddName::class ]
        ];
    }
    
    /**
     * Returns the mapping of received event name with associate event class name
     */
    public function getEventsTypeMap(): array
    {
        return [
            'UserAddName' => UserAddNameEvent::class
        ];
    }
}
