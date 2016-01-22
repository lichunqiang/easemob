<?php

namespace light\Easemob\Message;

class Voice extends Message
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $type = self::TYPE_VOICE;

    protected $properties = ['url', 'filename', 'length', 'secret'];
}
