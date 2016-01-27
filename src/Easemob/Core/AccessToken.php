<?php

namespace light\Easemob\Core;

use light\Easemob\Exception\HttpException;
use light\Easemob\Exception\InvalidArgumentException;
use light\Easemob\Support\Log;

/**
 * 获取管理员Token.
 *
 *
 */
class AccessToken
{
    /**
     * @var string client id of app
     */
    public $clientId;
    /**
     * @var string client secret of app
     */
    public $clientSecret;

    /**
     * @var string|array Cache component
     */
    public $cache = 'cache';

    /**
     * @var string
     */
    public $cacheKeyPrefix = 'easemob.access.token';

    /**
     * @var Http
     */
    public $http;

    protected $token;

    public function init()
    {
        if (null === $this->clientId
            || null === $this->clientSecret) {

            throw new InvalidArgumentException('Ether $clientId or $clientSecret should be speicied.');
        }
    }

    /**
     * Get the access token
     *
     * @param bool $forceRefresh
     *
     * @return string
     * @throws HttpException
     */
    public function getToken($forceRefresh = false)
    {
        if (!$this->token) {

            $this->token = $this->cache->get($this->cacheKeyPrefix);

            if ($forceRefresh || !$this->token) {

                $token = $this->getTokenFromServer();

                $this->cache->set($this->cacheKeyPrefix, $token['access_token'], $token['expires_in'] - 1500);

                $this->token = $token['access_token'];
            }
        }

        return $this->token;
    }

    /**
     * Internal fetch token logic
     *
     * @return array
     * @throws HttpException
     * @throws \light\Easemob\Exception\HttpException
     */
    protected function getTokenFromServer()
    {
        $this->http->clearMiddlewares();
        $response = $this->http->post('token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        Log::debug('Get access token response', ['response' => $response]);

        $token = $this->http->parseJSON((string) $response->getBody());

        if (!isset($token['access_token'])) {
            throw new HttpException('Request AccessToken fail.' . json_encode($token, JSON_UNESCAPED_UNICODE));
        }
        return $token;
    }
}
