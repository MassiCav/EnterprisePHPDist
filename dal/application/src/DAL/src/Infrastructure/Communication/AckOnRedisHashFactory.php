<?php declare(strict_types=1);

namespace DAL\Infrastructure\Communication;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Swoole\Database\RedisConfig;
use Swoole\Database\RedisPool;

class AckOnRedisHashFactory
{
    public function __invoke(ContainerInterface $container): AckOnRedisHash
    {
        $redisConfig = $container->get('config')['redis'] ?? [];
        if (empty($redisConfig)) {
            throw new ServiceNotFoundException("Could not find the 'redis' configuration in container!");
        }
        $this->validateConfig($redisConfig);
        $classConfig = (new RedisConfig)->withHost($redisConfig['host'])
                                        ->withPort($redisConfig['port'])
                                        ->withDbIndex($redisConfig['dbIndex'])
                                        ->withTimeout($redisConfig['timeout']);
        $pool = new RedisPool($classConfig, 16);
        return new AckOnRedisHash($pool);
    }

    /**
     * Validate retrieved data from configuration array
     *
     * @param array $config
     * @return void
     */
    private function validateConfig(array $config) {}
}
