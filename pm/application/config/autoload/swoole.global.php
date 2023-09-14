<?php declare(strict_types=1);

use PM\Log\TaskFinishLoggingListener;
use PM\Log\TaskStartLoggingListener;
use Mezzio\Swoole\Event\HotCodeReloaderWorkerStartListener;
use Mezzio\Swoole\Event\TaskEvent;
use Mezzio\Swoole\Event\TaskFinishEvent;
use Mezzio\Swoole\Event\WorkerStartEvent;
use Mezzio\Swoole\Log\AccessLogFormatter;
use Mezzio\Swoole\Task\TaskInvokerListener;

return [
    'mezzio-swoole' => [
        'enable_coroutine' => true,
        'hot-code-reload' => [
            'interval' => 500,
            'paths' => [
                '/pm/src',
                '/pm/config'
            ],
        ],
        'swoole-http-server' => [
            'host' => '0.0.0.0',
            'port' => 9502,
            'mode' => 1, // 1 SWOOLE_BASE, 2 SWOOLE_PROCESS
            'logger' => [
                'format' => AccessLogFormatter::FORMAT_COMBINED
            ],
            'options' => [
                // generic
                'max_coroutine' => 1024,
                // server
                'dispatch_mode' => 1,
                'worker_num' => 4,
                'http_parse_post' => true,
                'http_parse_cookie' => true,
                // task worker
                'task_max_request' => 100,
                'task_tmpdir' => '/tmp',
                'task_worker_num' => 4,
                'task_enable_coroutine' => true,
                'task_use_object' => true,
                // tcp
                'heartbeat_idle_time' => 10,
                'heartbeat_check_interval' => 1,
                'buffer_output_size' => 32 * 1024,
                'tcp_fastopen' => true,
                'max_conn' => 1024
            ],
            'listeners' => [
                WorkerStartEvent::class => [
                    HotCodeReloaderWorkerStartListener::class,
                ],
                TaskEvent::class => [
                    TaskStartLoggingListener::class,
                    TaskInvokerListener::class
                ],
                TaskFinishEvent::class => [
                    TaskFinishLoggingListener::class
                ],
            ],
            'process-name' => 'dal',
        ],
    ]
];