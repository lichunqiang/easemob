<?php

/*
 * This file is part of the light/easemob.
 *
 * (c) lichunqiang <light-li@hotmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require '../vendor/autoload.php';

$config = [
    'enterpriseId' => 'easemob-playground',
    'appId' => 'test1',
    'clientId' => 'YXA6wDs-MARqEeSO0VcBzaqg5A',
    'clientSecret' => 'YXA6JOMWlLap_YbI_ucz77j-4-mI0JA',
    'cachePath' => __DIR__ . '/../tests/runtime',
    'log' => [
        'file' => __DIR__ . '/../tests/runtime/easemob.log',
        'level' => \Monolog\Logger::DEBUG,
    ],

];
$easemob = new \light\Easemob\Easemob($config);
