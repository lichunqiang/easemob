<?php

/*
 * This file is part of the light/easemob.
 *
 * (c) lichunqiang <light-li@hotmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace light\Easemob\Support;

use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * Log.
 *
 * @method static debug($message, array $context)
 * @method static error($message, array $context)
 */
class Log
{
    /**
     * @var LoggerInterface
     */
    protected static $_logger;

    public static function setLogger(LoggerInterface $logger)
    {
        self::$_logger = $logger;
    }

    public static function getLogger()
    {
        return self::$_logger ?: self::$_logger = self::createDefaultLogger();
    }

    public static function __callStatic($method, $args)
    {
        return forward_static_call_array([self::getLogger(), $method], $args);
    }

    public function __call($method, $args)
    {
        return call_user_func_array([self::getLogger(), $method], $args);
    }

    public static function createDefaultLogger()
    {
        $logger = new Logger('easemob');
        $logger->pushHandler(new ErrorLogHandler());

        return $logger;
    }
}
