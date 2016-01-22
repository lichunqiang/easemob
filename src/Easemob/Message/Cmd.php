<?php

namespace light\Easemob\Message;

class Cmd extends Message
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $type = self::TYPE_CMD;

    protected $properties = ['action'];
}
