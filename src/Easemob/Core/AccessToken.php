<?php

/*
 * This file is part of the light/easemob.
 *
 * (c) lichunqiang <light-li@hotmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace light\Easemob\Core;

use Doctrine\Common\Cache\FilesystemCache;
use light\Easemob\Exception\HttpException;
use light\Easemob\Support\Log;

/**
 * Get the access token.
 */
class AccessToken
{
    /**
     * @var string client id of app
     */
    protected $clientId;
    /**
     * @var string client secret of app
     */
    protected $clientSecret;
    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    protected $cache;
    /**
     * @var string
     */
    protected $cacheKeyPrefix = 'easemob.access.token';
    /**
     * @var Http
     */
    protected $http;
    /**
     * @var string
     */
    protected $token;

    public function __construct(
        $clientId,
        $clientSecret,
        Http $http,
        $cache = null
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->http = $http;
        $this->cache = $cache;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param string $clientSecret
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return \Doctrine\Common\Cache\Cache
     */
    public function getCache()
    {
        return $this->cache ?: new FilesystemCache(sys_get_temp_dir());
    }

    /**
     * @param \Doctrine\Common\Cache\Cache $cache
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return Http
     */
    public function getHttp()
    {
        return $this->http;
    }

    /**
     * @param Http $http
     */
    public function setHttp($http)
    {
        $this->http = $http;
    }

    /**
     * Get the access token.
     *
     * @param bool $forceRefresh
     *
     * @throws HttpException
     *
     * @return string
     */
    public function getToken($forceRefresh = false)
    {
        if (!$this->token) {
            $this->token = $this->getCache()->fetch($this->cacheKeyPrefix);

            if ($forceRefresh || !$this->token) {
                $token = $this->getTokenFromServer();
                Log::debug('Renew the access token', [
                    'token' => $token,
                ]);

                $this->getCache()->save(
                    $this->cacheKeyPrefix,
                    $token['access_token'],
                    $token['expires_in'] - 1500
                );

                $this->token = $token['access_token'];
            }
        }

        return $this->token;
    }

    /**
     * Internal fetch token logic.
     *
     * @throws HttpException
     * @throws \light\Easemob\Exception\HttpException
     *
     * @return array
     */
    protected function getTokenFromServer()
    {
        $this->http->clearMiddlewares();
        $response = $this->http->post('token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        Log::debug('Get access token response', ['response' => (string) $response->getBody()]);

        $token = $this->http->parseJSON((string) $response->getBody());

        if (!isset($token['access_token'])) {
            throw new HttpException('Request AccessToken fail.' . json_encode($token, JSON_UNESCAPED_UNICODE));
        }

        return $token;
    }
}
