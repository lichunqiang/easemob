<?php

/*
 * This file is part of the light/easemob.
 *
 * (c) lichunqiang <light-li@hotmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace tests\Message;

use light\Easemob\Message\Cmd;

class CmdTest extends \PHPUnit_Framework_TestCase
{
    public function testAsArr()
    {
        $cmd = new Cmd(['action' => 'test']);
        $cmd->to = 1;
        $cmd->from = 2;
        $this->assertSame('test', $cmd->toArray()['action']);

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

        $this->assertSame('users', $result['target_type']);
        $this->assertSame(['action' => 'test', 'type' => 'cmd'], $result['msg']);
    }
}
