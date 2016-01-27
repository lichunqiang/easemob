<?php

namespace light\Easemob\Rest;

use light\Easemob\Core\AccessToken;
use light\Easemob\Core\Http;
use light\Easemob\Exception\EasemobException;
use light\Easemob\Support\Log;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class Rest
{
    /**
     * @var Http
     */
    protected $http;
    /**
     * @var \light\Easemob\Core\AccessToken
     */
    protected $accessToken;

    public function __construct(AccessToken $accessToken, Http $http)
    {
        $this->accessToken = $accessToken;
        $this->http = $http;

        $this->registerHttpMiddleware();
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
     * @return AccessToken
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param AccessToken $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Parse response
     *
     * @param ResponseInterface $response
     *
     * @return mixed
     * @throws EasemobException
     * @throws \light\Easemob\Exception\HttpException
     */
    public function parseResponse(ResponseInterface $response)
    {

        $result = $this->http->parseJSON($response);

        Log::debug('Parsed http response', ['response' => $result]);

        if (isset($result['error'])) {
            throw new EasemobException($result['error_description']);
        }
        return $result;
    }

    /**
     * Set request access_token query.
     */
    protected function registerHttpMiddleware()
    {
        // access token
        $this->http->addMiddleware($this->accessTokenMiddleware());
    }

    /**
     * Attache access token to request query.
     *
     * @return \Closure
     */
    public function accessTokenMiddleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {

                if (!$this->accessToken->getToken()) {
                    return $handler($request, $options);
                }

                $request = $request->withHeader('Authorization', 'Bearer ' . $this->accessToken->getToken());

                return $handler($request, $options);
            };
        };
    }
}
