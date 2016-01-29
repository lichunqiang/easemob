<?php

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
        $this->assertEquals('http://mock.com/estaDA12dsa.png', $image->toArray()['url']);

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

        $this->assertEquals('users', $result['target_type']);

        $this->assertEquals('http://mock.com/estaDA12dsa.png', $result['msg']['url']);
    }


}
