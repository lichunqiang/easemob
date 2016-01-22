<?php

namespace tests;

use light\Easemob\Easemob;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $easemob;

    public function setUp()
    {
        $this->easemob = new Easemob(
            env('easemob.ENTERPRISE_ID'),
            env('easemob.APP_ID'),
            env('easemob.CLIENT_ID'),
            env('easemob.CLIENT_SECRET')
        );
    }
}
