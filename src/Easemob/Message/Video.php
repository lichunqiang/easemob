<?php

namespace light\Easemob\Message;

class Video extends Message
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $type = self::TYPE_VIDEO;

    protected $properties = ['filename', 'thumb', 'length', 'secret', 'file_length', 'thumb_secret', 'url'];
}
