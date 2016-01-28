<?php
use Monolog\Logger;

require __DIR__ . '/../vendor/autoload.php';

//clear all the data from the server.
//$easemob = new \light\Easemob\Easemob([
//    'enterpriseId' => getenv('enterpriseId'),
//    'appId' => getenv('appId'),
//    'clientId' => getenv('clientId'),
//    'clientSecret' => getenv('clientSecret'),
//    'log' => [
//        'file' => __DIR__ . '/runtime/easemob.log',
//        'level' => Logger::DEBUG
//    ]
//]);
//
//$result = $easemob->user->batchRemove();
