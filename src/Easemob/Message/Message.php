<?php

namespace light\Easemob\Message;

use light\Easemob\Support\Arr;
use light\Easemob\Support\Attribute;

class Message extends Attribute
{
    const TYPE_TEXT = 'txt';
    const TYPE_IMG = 'img';
    const TYPE_VOICE = 'audio';
    const TYPE_VIDEO = 'video';
    const TYPE_CMD = 'cmd';

    /**
     * Message type.
     *
     * @var string
     */
    protected $type;

    /**
     * Target type
     *
     * @var string
     */
    protected $target_type;

    /**
     * Message target user open id.
     *
     * @var string
     */
    protected $to;

    /**
     * Message sender open id.
     *
     * @var string
     */
    protected $from;

    /**
     * Extra attributes
     *
     * @var array
     */
    protected $ext;

    /**
     * Message attributes.
     *
     * @var array
     */
    protected $properties = [];

    protected $alias = [
        'target' => 'to',
        'score' => 'target_type',
    ];

    /**
     * Constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct(Arr::only($attributes, $this->properties));
    }

    public function build()
    {
        $body = (new MessageBuilder($this))->build();

        return $body;
    }

    /**
     * Return type name message.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Magic getter.
     *
     * @param string $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

        return parent::__get($property);
    }

    /**
     * Magic setter.
     *
     * @param string $property
     * @param mixed  $value
     *
     * @return AbstractMessage
     */
    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        } else {
            parent::__set($property, $value);
        }

        return $this;
    }

    protected function validateSelf()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        //we should do some check
        $this->validateSelf();
        $data = parent::toArray();

        $data['type'] = $this->type;

        return $data;
    }
}
