<?php

namespace tests\Message;

use light\Easemob\Message\Cmd;
use light\Easemob\Message\Image;
use light\Easemob\Message\Text;
use light\Easemob\Message\Video;
use light\Easemob\Message\Voice;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function testTxtMsg()
    {
        $text = new Text();

        $text->msg = 'hello';

        $text->to = ['l2312312', '123123'];
        $text->from = '15210345047';

        var_dump($text->send());
    }

    public function testImageMsg()
    {
        $image = new Image([
            'url' => 'http://api.iyuban.com',
            'filename' => '123.jpg',
            'secret' => 'dsadasdasd',
            'size' => [
                'width' => 123,
                'height' => 1231,
            ],
        ]);

        $image->to = ['123123', '12312'];
        $image->from = '312312312';

        var_dump($image->send());
    }

    public function testCmd()
    {
        $cmd = new Cmd(['action' => 'dsadas']);
        $cmd->to = ['123123', '12312'];
        $cmd->from = '312312312';

        var_dump($cmd->send());
    }

    public function testVideo()
    {
        $video = new Video([
            'url' => 'http://api.iyuban.com',
            'filename' => '123.jpg',
            'secret' => 'dsadasdasd',
            'size' => [
                'width' => 123,
                'height' => 1231,
            ],
        ]);

        $video->to = ['123123', '12312'];
        $video->from = '312312312';

        var_dump($video->send());
    }

    public function testVoice()
    {
        $voice = new Voice([
            'url' => 'http://api.iyuban.com',
            'filename' => '123.jpg',
            'secret' => 'dsadasdasd',
            'size' => [
                'width' => 123,
                'height' => 1231,
            ],
        ]);

        $voice->to = ['123123', '12312'];
        $voice->from = '312312312';

        var_dump($voice->send());
    }
}
