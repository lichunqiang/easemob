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

use light\Easemob\Message\Image;
use tests\TestCase;

class ImageTest extends TestCase
{
    public function testAsArr()
    {
        $image = new Image([
            'url' => 'http://mock.com/estaDA12dsa.png',
            'filename' => 'estaDA12dsa.png',
            'secret' => 'fdsfadsfaf',
        ]);
        $image->to = 1;
        $image->from = 2;
        $this->assertSame('http://mock.com/estaDA12dsa.png', $image->toArray()['url']);

        return $image;
    }

    /**
     * @depends testAsArr
     *
     * @param \light\Easemob\Message\Image $image
     */
    public function testBuild($image)
    {
        $result = $image->build();

        $this->assertSame('users', $result['target_type']);

        $this->assertSame('http://mock.com/estaDA12dsa.png', $result['msg']['url']);
    }
}
