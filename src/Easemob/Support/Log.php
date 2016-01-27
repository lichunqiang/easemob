<?php

namespace light\Easemob\Support;

use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * Log
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
