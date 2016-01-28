<?php

namespace tests\Message;

use light\Easemob\Message\Cmd;

class CmdTest extends \PHPUnit_Framework_TestCase
{

    public function testAsArr()
    {
        $cmd = new Cmd(['action' => 'test']);
        $cmd->to = 1;
        $cmd->from = 2;
        $this->assertEquals('test', $cmd->toArray()['action']);

        return $cmd;
    }

    /**
     * @depends testAsArr
     *
     * @param \light\Easemob\Message\Cmd $cmd
     */
    public function testBuild($cmd)
    {
        $result = $cmd->build();

        $this->assertEquals('users', $result['target_type']);
        $this->assertEquals(['action' => 'test', 'type' => 'cmd'], $result['msg']);
    }
}
