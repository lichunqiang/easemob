<?php

namespace light\Easemob\Rest;

use light\Easemob\Core\Http;
use light\Easemob\Exception\EasemobException;
use light\Easemob\Support\Log;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use yii\base\Object;

abstract class Rest extends Object
{
    /**
     * @var Http
     */
    public $http;

    /**
     * @var light\Easemob\Core\AccessToken
     */
    public $token;

    public function init()
    {
        $this->registerHttpMiddleware();
    }

    /**
     * Parse response
     *
     * @param  Response $response
     *
     * @return mixed
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
     * @return Closure
     */
    public function accessTokenMiddleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {

                if (!$this->token->getToken()) {
                    return $handler($request, $options);
                }

                $request = $request->withHeader('Authorization', 'Bearer ' . $this->token->getToken());

                return $handler($request, $options);
            };
        };
    }
}
