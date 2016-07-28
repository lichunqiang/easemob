<?php

/*
 * This file is part of the light/easemob.
 *
 * (c) lichunqiang <light-li@hotmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace tests;

use light\Easemob\Easemob;
use Monolog\Logger;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $easemob;

    public function setUp()
    {
        $this->easemob = new Easemob([
            'enterpriseId' => getenv('enterpriseId'),
            'appId' => getenv('appId'),
            'clientId' => getenv('clientId'),
            'clientSecret' => getenv('clientSecret'),
            'log' => [
                'file' => __DIR__ . '/runtime/easemob.log',
                'level' => Logger::DEBUG,
            ],
        ]);
    }
}
