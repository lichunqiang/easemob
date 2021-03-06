<?php

/*
 * This file is part of the light/easemob.
 *
 * (c) lichunqiang <light-li@hotmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace light\Easemob;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\FilesystemCache;
use light\Easemob\Core\AccessToken;
use light\Easemob\Core\Config;
use light\Easemob\Core\Http;
use light\Easemob\Providers\ChatProvider;
use light\Easemob\Providers\ChatRoomProvider;
use light\Easemob\Providers\FileProvider;
use light\Easemob\Providers\GroupProvider;
use light\Easemob\Providers\MessageProvider;
use light\Easemob\Providers\UserProvider;
use light\Easemob\Support\Log;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class Easemob.
 *
 * @property \light\Easemob\Rest\User user
 * @property \light\Easemob\Rest\Message message
 * @property \light\Easemob\Rest\File file
 * @property \light\Easemob\Rest\Chat chat
 * @property \light\Easemob\Rest\ChatRoom chatroom
 * @property \light\Easemob\Rest\Group group
 */
class Easemob extends Container
{
    const BASE_URL = 'https://a1.easemob.com';

    /**
     * Core service providers.
     *
     * @var array
     */
    protected $coreProviders = [
        UserProvider::class,
        ChatProvider::class,
        ChatRoomProvider::class,
        FileProvider::class,
        GroupProvider::class,
        MessageProvider::class,
    ];

    /**
     * @var string
     */
    protected $api;

    public function __construct(array $config)
    {
        parent::__construct();

        $this['config'] = function () use ($config) {
            return new Config($config);
        };

        //init api url
        $this->api = self::BASE_URL . '/' . $this['config']['enterpriseId'] . '/' . $this['config']['appId'] . '/';

        $this->registerCoreProviders();
        $this->registerProviders();
        $this->initializeLogger();

        Log::debug('Configuration:', ['config' => $this['config']]);
    }

    /**
     * Get all providers.
     *
     * @return array
     */
    public function getProviders()
    {
        return $this->coreProviders;
    }

    /**
     * @param ServiceProviderInterface $provider
     *
     * @return $this
     */
    public function setProvider(ServiceProviderInterface $provider)
    {
        $this->coreProviders[] = $provider;

        return $this;
    }

    /**
     * @param array $providers
     */
    public function setProviders(array $providers)
    {
        $this->coreProviders = [];

        foreach ($providers as $provider) {
            $this->setProvider($provider);
        }
    }

    /**
     * Register providers.
     */
    protected function registerProviders()
    {
        foreach ($this->coreProviders as $provider) {
            $this->register(new $provider());
        }
    }

    /**
     * Register core providers.
     */
    protected function registerCoreProviders()
    {
        $this['http'] = function () {
            return new Http($this->api);
        };

        $this['cache'] = function () {
            return new FilesystemCache($this['config']['cachePath'] ?: sys_get_temp_dir());
        };

        $this['access_token'] = function () {
            return new AccessToken(
                $this['config']['clientId'],
                $this['config']['clientSecret'],
                $this['http'],
                $this['cache']
            );
        };
    }

    /**
     * Init logger.
     */
    private function initializeLogger()
    {
        $logger = new Logger('Easemob');

        if ($this['config']['debug']) {
            $logger->pushHandler(new NullHandler());
        } elseif ($logFile = $this['config']['log.file']) {
            $logger->pushHandler(new StreamHandler(
                $logFile,
                $this['config']->get('log.level') ?: Logger::WARNING
            ));
        }

        Log::setLogger($logger);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * @param string $name
     */
    public function __unset($name)
    {
        $this->offsetUnset($name);
    }
}
