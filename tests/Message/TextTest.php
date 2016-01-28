<?php

namespace tests\messages;

use light\Easemob\Message\Text;

class TextTest extends \PHPUnit_Framework_TestCase
{
    public function testAsArr()
    {
        $text = new Text(['msg' => 'hello']);
        $text->to = 1;
        $text->from = 2;

        $this->assertEquals('hello', $text->toArray()['msg']);
        return $text;
    }

    public function testAssign()
    {
        $text = new Text();
        $text->msg = 'hello';
        $text->to = '1';
        $text->from = 2;

        $this->assertEquals('hello', $text->toArray()['msg']);
    }

    /**
     * @depends testAsArr
     * @param \light\Easemob\Message\Text $text
     */
    public function testBuild($text)
    {
        $result = $text->build();

        $this->assertEquals('users', $result['target_type']);
        $this->assertEquals([
            'type' => 'txt',
            'msg' => 'hello'
        ], $result['msg']);
    }
}
