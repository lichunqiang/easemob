<?php

/*
 * This file is part of the light/easemob.
 *
 * (c) lichunqiang <light-li@hotmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

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
     * {@inheritdoc}
     */
    protected function validateSelf()
    {
        parent::validateSelf();
        foreach ($this->properties as $prop) {
            if (empty($this->{$prop})) {
                throw new InvalidArgumentException("{$prop} can not be null");
            }
        }
    }
}
