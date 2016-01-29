<?php

namespace light\Easemob\Message;

use light\Easemob\Exception\InvalidArgumentException;

class Image extends Message
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $type = self::TYPE_IMG;

    protected $properties = ['url', 'filename', 'secret', 'size'];

    /**
     * @inheritdoc
     */
    protected function validateSelf()
    {
        parent::validateSelf();
        foreach($this->properties as $prop) {
            if ('size' == $prop) {
                continue;
            }
            if (empty($this->{$prop})) {
                throw new InvalidArgumentException("{$prop} can not be null");
            }
        }
    }

}
