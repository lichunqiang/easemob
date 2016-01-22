<?php

namespace light\Easemob\Support;

use light\Easemob\Adpter\Logger;
use Psr\Log\LoggerInterface;
use Yii;

/**
 * Log
 */
class Log
{
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
        $logger = new Logger(Yii::getAlias('@runtime/logs/easemob.log'));

        return $logger;
    }
}
