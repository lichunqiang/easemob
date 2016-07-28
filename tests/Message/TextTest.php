<?php

/*
 * This file is part of the light/easemob.
 *
 * (c) lichunqiang <light-li@hotmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace tests\messages;

use light\Easemob\Message\Text;

class TextTest extends \PHPUnit_Framework_TestCase
{
    public function testAsArr()
    {
        $text = new Text(['msg' => 'hello']);
        $text->to = 1;
        $text->from = 2;

        $this->assertSame('hello', $text->toArray()['msg']);

        return $text;
    }

    public function testAssign()
    {
        $text = new Text();
        $text->msg = 'hello';
        $text->to = '1';
        $text->from = 2;

        $this->assertSame('hello', $text->toArray()['msg']);
    }

    /**
     * @depends testAsArr
     *
     * @param \light\Easemob\Message\Text $text
     */
    public function testBuild($text)
    {
        $result = $text->build();

        $this->assertSame('users', $result['target_type']);
        $this->assertSame([
            'type' => 'txt',
            'msg' => 'hello',
        ], $result['msg']);
    }
}
