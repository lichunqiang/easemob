<?php

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
 * Class Easemob
 *
 * @package light\Easemob
 */
class Easemob extends Container
{
    const BASE_URL = 'https://a1.easemob.com';

    /**
     * Core service providers
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
            $this->register(new $provider);
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
     * Init logger
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
}
