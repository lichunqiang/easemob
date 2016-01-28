<?php

namespace light\Easemob\Message;

use light\Easemob\Exception\InvalidArgumentException;

class Text extends Message
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $type = self::TYPE_TEXT;
    /**
     * @inheritdoc
     */
    protected $properties = ['msg'];

    /**
     * @inheritdoc
     */
    protected function validateSelf()
    {
        parent::validateSelf();
        if (empty($this->msg)) {
            throw new InvalidArgumentException('msg must be set.');
        }
    }
}
