<?php declare(strict_types=1);

namespace DAL\Domain\User\Command\Command;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class UserAddNameFactory
{
    public function __invoke(ContainerInterface $container): UserAddName
    {
        return new UserAddName($container->get(EntityManagerInterface::class));
    }
}