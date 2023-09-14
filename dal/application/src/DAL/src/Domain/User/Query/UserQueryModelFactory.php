<?php declare(strict_types=1);

namespace DAL\Domain\User\Query;

use DAL\Commanding\Domain\Command;
use DAL\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;

class UserQueryModelFactory
{
    public static $repoIdentifier = User::class;

    public function __invoke(ContainerInterface $container): UserQueryModel
    {
        $em = $container->get(EntityManagerInterface::class);
        return new UserQueryModel($em->getRepository(self::$repoIdentifier));
    }
}
