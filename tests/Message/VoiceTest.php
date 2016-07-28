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

use light\Easemob\Exception\InvalidArgumentException;
use light\Easemob\Message\Voice;
use tests\TestCase;

class VoiceTest extends TestCase
{
    public function testAsArr()
    {
        $video = new Voice([
            'filename' => 'test.arm',
            'length' => 12,
            'secret' => 'fdsfsafs123dSDWaa',
            'url' => 'http://www.tset.com',
        ]);
        $video->to = 1;
        $video->from = 2;
        $arr = $video->toArray();

        $this->assertSame('test.arm', $arr['filename']);
        $this->assertArrayHasKey('secret', $arr);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBadArgument()
    {
        $voice = new Voice(['filename' => 'test']);
        $voice->toArray();
    }
}
