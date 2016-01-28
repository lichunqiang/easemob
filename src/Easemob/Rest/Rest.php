<?php

namespace light\Easemob\Rest;

use light\Easemob\Core\AccessToken;
use light\Easemob\Core\Http;
use light\Easemob\Exception\EasemobException;
use light\Easemob\Exception\MethodNotFoundException;
use light\Easemob\Support\Log;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Rest
 *
 * @method post($uri, $options = [])
 * @method get($uri, $options = [])
 * @method put($uri, $options = [])
 * @method delete($uri, $options = [])
 * @method upload($uri, $options = [])
 */
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
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws MethodNotFoundException
     */
    public function __call($name, $arguments)
    {
        $httpMethods = ['GET', 'POST', 'DELETE', 'PUT', 'UPLOAD'];

        if (in_array(strtoupper($name), $httpMethods)) {
            return $this->parseResponse(
                call_user_func_array([$this->http, $name], $arguments)
            );
        }
        throw new MethodNotFoundException('Call undefined method.');
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
    protected function parseResponse(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();
        if (200 !== $statusCode) {
            //通用false代表此次请求失败，并没有成功
            Log::error('Got an error reponse:', [
                '__class__' => get_called_class(),
                'code' => $statusCode,
                'responseBody' => (string)$response->getBody(),
            ]);
            return false;
        }
        $result = $this->http->parseJSON($response);

        Log::debug('Parse response body result:', ['response' => $result]);

        if (isset($result['error'])) {
            Log::error('Got an error from server:', [
                '__class__' => get_called_class(),
                'error' => $result,
            ]);
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
