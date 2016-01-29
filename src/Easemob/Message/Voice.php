<?php

namespace light\Easemob\Message;

use light\Easemob\Exception\InvalidArgumentException;

class Voice extends Message
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $type = self::TYPE_VOICE;

    protected $properties = ['url', 'filename', 'length', 'secret'];

    /**
     * @inheritdoc
     */
    protected function validateSelf()
    {
        parent::validateSelf();
        foreach($this->properties as $prop) {
            if (empty($this->{$prop})) {
                throw new InvalidArgumentException("{$prop} can not be null");
            }
        }
    }

}
