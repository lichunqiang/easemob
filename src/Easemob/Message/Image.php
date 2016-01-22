<?php

namespace light\Easemob\Message;

class Image extends Message
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $type = self::TYPE_IMG;

    protected $properties = ['url', 'filename', 'secret', 'size'];
}
