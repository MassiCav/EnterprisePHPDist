<?php declare(strict_types=1);

namespace DAL\Infrastructure\Persistence;

use Closure;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager as ORMEntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use OpsWay\Doctrine\ORM\Swoole\EntityManager;
use OpsWay\Doctrine\ORM\Swoole\EntityManagerDelegator;

class EntityManagerFactory
{
    public Configuration $ormConfig;
    public array $dbConfig;

    public function __invoke(ContainerInterface $container): EntityManager
    {
        $dbConfig = $container->get('config')['db'] ?? [];
        if (empty($dbConfig)) {
            throw new ServiceNotFoundException("Could not find the 'db' related configuration in container!");
        }
        $this->validateDbConfig($dbConfig);
        $this->dbConfig = $dbConfig;
        $this->ormConfig = ORMSetup::createAttributeMetadataConfiguration(
            $paths = [DAL_MODULE_PATH.'/Domain/User/Entity'],
            $isDevMode = true
        );
        $emFactoryFn = Closure::fromCallable(
           [$this, 'createEnityManager']
        );
        return (new EntityManagerDelegator)($container, EntityManagerInterface::class, $emFactoryFn);

    }

    /**
     * Create Doctrine Entity Manager instance
     *
     * @return void
     */
    public function createEnityManager(): EntityManagerInterface 
    {
        return ORMEntityManager::create($this->dbConfig, $this->ormConfig);
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
