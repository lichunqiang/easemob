<?php

namespace light\Easemob\Message;

use light\Easemob\Exception\InvalidArgumentException;

class Video extends Message
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $type = self::TYPE_VIDEO;

    protected $properties = ['filename', 'thumb', 'length', 'secret', 'file_length', 'thumb_secret', 'url'];

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
