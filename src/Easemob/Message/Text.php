<?php

namespace light\Easemob\Message;

class Text extends Message
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $type = self::TYPE_TEXT;

    protected $properties = ['msg'];
}
