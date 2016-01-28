<?php

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
                'level' => Logger::DEBUG
            ]
        ]);
    }
}
