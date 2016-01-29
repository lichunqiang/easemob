<?php

namespace tests\Message;

use light\Easemob\Exception\InvalidArgumentException;
use light\Easemob\Message\MessageBuilder;
use light\Easemob\Message\Text;
use tests\TestCase;

class MessageBuilderTest extends TestCase
{

    public function testBuild()
    {
        $text = new Text(['msg' => 'hello']);
        $builder = new MessageBuilder($text);
        $builder->to([1]);
        $builder->from(2);

        $arr = $builder->build();
        $this->assertArrayHasKey('msg', $arr);

        return $text;
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testWrongArg()
    {
        $text = new Text(['msg' => 'hello']);
        $builder = new MessageBuilder($text);
        $builder->build();
    }
}
