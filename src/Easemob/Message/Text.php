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

/**
 * Class Text.
 *
 * @property string $msg
 */
class Text extends Message
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $type = self::TYPE_TEXT;
    /**
     * {@inheritdoc}
     */
    protected $properties = ['msg'];

    /**
     * {@inheritdoc}
     */
    protected function validateSelf()
    {
        parent::validateSelf();
        if (empty($this->msg)) {
            throw new InvalidArgumentException('msg must be set.');
        }
    }
}
