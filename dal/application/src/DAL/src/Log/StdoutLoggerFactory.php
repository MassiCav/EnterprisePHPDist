<?php declare(strict_types=1);

namespace DAL\Log;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;

/**
 * Application basic logger
 */
class StdoutLoggerFactory
{
    /**
     * Logging to 'stdout'
     *
     * @param ContainerInterface $container
     * @return LoggerInterface
     */
    public function __invoke(ContainerInterface $container) : LoggerInterface
    {
        $level = Logger::INFO;
        $config = $container->get('config');
        $debug = $config['debug'] ?? false;
        if (true == $debug) $level = Logger::DEBUG;
        $logger = new Logger('swoole-http-server');
        $logger->pushHandler(new StreamHandler(
            'php://stdout',
            $level,
            true
        ));
        $logger->pushProcessor(new PsrLogMessageProcessor());
        return $logger;
    }
}