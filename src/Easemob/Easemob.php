<?php

namespace light\Easemob;

use yii\di\ServiceLocator;

class Easemob extends ServiceLocator
{
    const BASE_URL = 'https://a1.easemob.com';

    /**
     * @var string 企业ID
     */
    protected $enterpriseId;
    /**
     * @var string APP名称
     */
    protected $appId;

    protected $clientId;

    protected $clientSecret;

    /**
     * @var string
     */
    public $api;

    public function __construct(
        $enterpriseId, $appId,
        $clientId, $clientSecret,
        $config = []
    ) {

        $this->enterpriseId = $enterpriseId;
        $this->appId = $appId;

        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        //init api url
        $this->api = self::BASE_URL . '/' . $enterpriseId . '/' . $appId . '/';

        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        //set base components
        $this->set('http', [
            'class' => Core\Http::class,
            'baseUri' => $this->api,
        ]);

        $this->set('token', [
            'class' => Core\AccessToken::class,
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
            'http' => $this->get('http'),
            'cache' => [
                'class' => 'yii\caching\FileCache',
            ],
        ]);

        $components = $this->coreComponents();
        foreach ($components as $id => $defination) {
            $defination['http'] = $this->get('http');
            $defination['token'] = $this->get('token');
            $this->set($id, $defination);
        }
    }

    /**
     * Core components
     *
     * @return array
     */
    protected function coreComponents()
    {
        return [
            'user' => ['class' => Rest\User::class],
            'chat' => ['class' => Rest\Chat::class],
            'file' => ['class' => Rest\File::class],
            'group' => ['class' => Rest\Group::class],
            'message' => ['class' => Rest\Message::class],
            'chatroom' => ['class' => Rest\ChatRoom::class],
        ];
    }
}
