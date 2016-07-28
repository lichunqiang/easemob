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

class MessageBuilder
{
    /**
     * Message send targets.
     *
     * @var array
     */
    protected $to;

    /**
     * Message send source user.
     *
     * This field can not empty, If not present this field, system default is admin.
     *
     * @var string
     */
    protected $from;

    /**
     * Message object.
     *
     * @var Message
     */
    protected $msg;

    /**
     * target type, default is `users`.
     *
     * @var string
     */
    protected $targetType = 'users';

    /**
     * Extra attributes.
     *
     * @var array
     */
    protected $ext;

    /**
     * Supported message send targets.
     *
     * @var array
     */
    protected $targetTypes = [
        'users',
        'chatgroups',
        'chatrooms',
    ];

    /**
     * MessageBuilder constructor.
     *
     * @param Message $msg
     */
    public function __construct(Message $msg)
    {
        $this->msg = $msg;

        $this->to = (array) $msg->to;
        $this->from = $msg->from;
        if ($msg->scope) {
            $this->targetType = $msg->scope;
        }
    }

    /**
     * Set target users.
     *
     * @param array $target
     *
     * @return $this
     */
    public function to(array $target)
    {
        $this->msg->to = $this->to = $target;

        return $this;
    }

    /**
     * Set from user.
     *
     * @param string $from
     *
     * @return $this
     */
    public function from($from)
    {
        $this->msg->from = $this->from = $from;

        return $this;
    }

    /**
     * Set target_type.
     *
     * @param string $type
     *
     * @throws InvalidArgumentException
     *
     * @return $this
     */
    public function setTargetType($type)
    {
        if (!in_array($type, $this->targetTypes)) {
            throw new InvalidArgumentException("{$type} is not supported.");
        }
        $this->targetType = $type;

        return $this;
    }

    public function build()
    {
        $data = [
            'target_type' => $this->targetType,
            'target' => (array) $this->to,
            'msg' => $this->msg->toArray(),
            'from' => $this->from,
            'ext' => $this->ext,
        ];

        $this->validate($data);

        return $data;
    }

    protected function validate(&$data)
    {
        $data = array_filter($data);

        return true;
    }
}
