<?php declare(strict_types=1);

namespace DAL\Infrastructure\Persistence;

use Doctrine\ORM\EntityManager as ORMEntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;

class EMCLIFactory
{
    public function __invoke(ContainerInterface $container): ORMEntityManager
    {
        $dbConfig = $container->get('config')['db'] ?? [];
        if (empty($dbConfig)) {
            throw new ServiceNotFoundException("Could not find the 'db' related configuration in container!");
        }
        $this->validateDbConfig($dbConfig);
        $ormConfig = ORMSetup::createAttributeMetadataConfiguration(
            $paths = [DAL_MODULE_PATH.'/Domain/User/Entity'],
            $isDevMode = true
        );
        return ORMEntityManager::create($dbConfig, $ormConfig);
    }

    /**
     * Throws an exception if given DB configuration is not valid
     *
     * @param array $config
     * @return void
     */
    private function validateDbConfig(array $config): void
    {}
}
