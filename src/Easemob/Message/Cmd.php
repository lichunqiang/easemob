<?php

namespace light\Easemob\Message;

use light\Easemob\Exception\InvalidArgumentException;

class Cmd extends Message
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $type = self::TYPE_CMD;

    protected $properties = ['action'];
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
