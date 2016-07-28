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
use light\Easemob\Message\Video;
use tests\TestCase;

class VideoTest extends TestCase
{
    public function testAsArr()
    {
        $video = new Video([
            'filename' => 'test.arm',
            'thumb' => 'htt://test.com/thub.png',
            'length' => 12,
            'secret' => 'fdsfsafs123dSDWaa',
            'file_length' => 123,
            'thumb_secret' => 'dsad1231dsadsASddas',
            'url' => 'http://www.tset.com',
        ]);
        $video->to = 1;
        $video->from = 2;
        $arr = $video->toArray();

        $this->assertSame('test.arm', $arr['filename']);
        $this->assertArrayHasKey('thumb_secret', $arr);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBadArgument()
    {
        $video = new Video(['filename' => 'test']);
        $video->toArray();
    }
}
