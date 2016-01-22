<?php

namespace light\Easemob\Adpter;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use yii\log\FileTarget;
use yii\log\Logger as YiiLogger;

/**
 * Psr log adpter for yii log system.
 */
class Logger extends AbstractLogger
{
    protected $logger;

    public function __construct($filePath)
    {
        $this->logger = new FileTarget([
            'logVars' => [], //clear the default log php vars
            'logFile' => $filePath,
        ]);
    }

    public function log($level, $message, array $context = [])
    {
        $this->logger->messages = [call_user_func_array([$this, 'packMessage'], func_get_args())];

        //export the logs
        $this->logger->export();
    }

    protected function packMessage($level, $message, $context = [])
    {
        $level_map = [
            LogLevel::EMERGENCY => YiiLogger::LEVEL_ERROR,
            LogLevel::ALERT => YiiLogger::LEVEL_ERROR,
            LogLevel::CRITICAL => YiiLogger::LEVEL_ERROR,
            LogLevel::ERROR => YiiLogger::LEVEL_ERROR,
            LogLevel::WARNING => YiiLogger::LEVEL_WARNING,
            LogLevel::NOTICE => YiiLogger::LEVEL_TRACE,
            LogLevel::INFO => YiiLogger::LEVEL_INFO,
            LogLevel::DEBUG => YiiLogger::LEVEL_TRACE,
        ];
        return [
            $context,
            $level_map[$level],
            $message,
            microtime(true),
        ];
    }
}
