<?php declare(strict_types=1);

namespace PM\Infrastructure\Communication;

use OpenSwoole\Core\Coroutine\Client\RedisClientFactory;
use OpenSwoole\Core\Coroutine\Client\RedisConfig;
use OpenSwoole\Core\Coroutine\Pool\ClientPool;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;


class AckOnRedisHashFactory
{
    public function __invoke(ContainerInterface $container): AckOnRedisHash
    {
        $redisConfig = $container->get('config')['redis'] ?? [];
        if (empty($redisConfig)) {
            throw new ServiceNotFoundException("Could not find the 'redis' configuration in container!");
        }
        $this->validateConfig($redisConfig);
        $redisConfig = (new RedisConfig)->withHost($redisConfig['host'])
                                      ->withPort($redisConfig['port'])
                                      ->withDbIndex($redisConfig['dbIndex'])
                                      ->withTimeout($redisConfig['timeout']);
        $pool = new ClientPool(RedisClientFactory::class, $redisConfig, 8, true);
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
