<?php

/*
 * This file is part of the light/easemob.
 *
 * (c) lichunqiang <light-li@hotmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace light\Easemob\Rest;

use Psr\Http\Message\RequestInterface;

/**
 * Class File.
 *
 * ~~~
 * $file = $easemob->file;
 * $result = $file->upload('/home/1.png');
 * $file_url = $file->url($result['uuid']);
 * ~~~
 */
class File extends Rest
{
    /**
     * {@inheritdoc}
     */
    protected function registerHttpMiddleware()
    {
        parent::registerHttpMiddleware();

        $this->http->addMiddleware($this->RestrictAccessMiddleware());
    }

    /**
     * @return \Closure
     */
    public function RestrictAccessMiddleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {

                if (!$this->accessToken->getToken()) {
                    return $handler($request, $options);
                }

                $request = $request->withHeader('restrict-access ', 'true');

                return $handler($request, $options);
            };
        };
    }

    /**
     * 上传文件.
     *
     * ~~~
     * [
     *     'uuid' => 'd8329590-c018-11e5-a936-0f89b914ff91',
     *     'type' => 'chatfile',
     *     'share-secret' => '2DKVmsAYEeWn7wsNJSQy-QxD0mQSSXd6jqC7PpD7_YQuIDr6'
     * ]
     * ~~~
     *
     * @param string $file
     *
     * @return array
     */
    public function upload($file)
    {
        $response = $this->http->upload('chatfiles', [
            'file' => $file,
        ]);

        return array_shift($this->parseResponse($response)['entities']);
    }

    /**
     * Return the resource web access url.
     *
     * @param string $uuid
     *
     * @return string
     */
    public function url($uuid)
    {
        return (string) $this->http->buildUri("chatfiles/{$uuid}");
    }
}
