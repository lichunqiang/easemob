<?php

namespace light\Easemob\Core;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request;
use light\Easemob\Exception\HttpException;
use light\Easemob\Support\Log;
use Psr\Http\Message\ResponseInterface;

class Http
{
    /**
     * @var string The base request url.
     */
    protected $baseUri;
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;
    /**
     * @var array The middlewares.
     */
    protected $middlewares = [];

    public function __construct($baseUri)
    {
        $this->baseUri = $baseUri;
    }

    /**
     * Set http client
     *
     * @param HttpClient $client
     *
     * @return $this
     */
    public function setClient(HttpClient $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get http client
     *
     * @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        if (!($this->client instanceof HttpClient)) {
            $this->client = new HttpClient([
                'base_uri' => $this->baseUri,
                'verify' => false,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);
        }
        return $this->client;
    }

    public function buildUri($path)
    {
        return Psr7\Uri::resolve(Psr7\uri_for($this->baseUri), $path);
    }

    /**
     * Make request
     *
     * @param  string $url
     * @param  string $method
     * @param  array  $options
     *
     * @return mixed
     */
    public function request($url, $method = 'GET', $options = [])
    {
        $method = strtoupper($method);

        $options['handler'] = $this->getHandler();

        try {
            $response = $this->getClient()->request($method, $url, $options);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            Log::error('Http Exception', ['response' => (string) $response->getBody()]);
        }

        return $response;
    }

    /**
     * Send a get request
     *
     * @param  string $url
     * @param  array  $options
     *
     * @return mixed
     */
    public function get($url, array $options = [])
    {
        return $this->request($url, 'GET', ['query' => $options]);
    }

    /**
     * Send a post request
     *
     * @param  string $url
     * @param  array  $options
     *
     * @return mixed
     */
    public function post($url, $options = [])
    {
        $options = is_array($options) ? json_encode($options, JSON_UNESCAPED_UNICODE) : $options;
        return $this->request($url, 'POST', ['body' => $options]);
    }

    /**
     * Send a delete request
     *
     * @param  string $url
     * @param  array  $options
     *
     * @return mixed
     */
    public function delete($url, $options = [])
    {
        return $this->request($url, 'DELETE', $options);
    }

    /**
     * Send a put request
     *
     * @param  string $url
     * @param  array  $options
     *
     * @return mixed
     */
    public function put($url, $options = [])
    {
        return $this->request($url, 'PUT', $options);
    }

    /**
     * Upload file.
     *
     * @param string $url
     * @param array $files
     * @param array $form
     *
     * @param array $queries
     *
     * @return ResponseInterface
     */
    public function upload($url, array $files = [], array $form = [], array $queries = [])
    {
        $multipart = [];

        foreach ($files as $name => $path) {
            $multipart[] = [
                'name' => $name,
                'contents' => fopen($path, 'r'),
            ];
        }

        foreach ($form as $name => $contents) {
            $multipart[] = compact('name', 'contents');
        }

        return $this->request($url, 'POST', ['query' => $queries, 'multipart' => $multipart]);
    }

    /**
     * Parse json data.
     *
     * @param  string $body
     *
     * @param bool $throws
     *
     * @return array
     * @throws HttpException
     */
    public function parseJSON($body, $throws = true)
    {
        if ($body instanceof ResponseInterface) {
            $body = $body->getBody();
        }

        $contents = json_decode($body, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            Log::error('Failed to parse JSON.', ['body' => $body]);
            throw new HttpException('Failed to parse JSON.', json_last_error());
        }

        return $contents;
    }

    /**
     * Add a middleware.
     *
     * @param callable $middleware
     *
     * @return $this
     */
    public function addMiddleware(callable $middleware)
    {
        array_push($this->middlewares, $middleware);

        return $this;
    }

    public function clearMiddlewares()
    {
        $this->middlewares = [];
        return $this;
    }

    /**
     * Build a handler.
     *
     * @return HandlerStack
     */
    protected function getHandler()
    {
        $stack = HandlerStack::create();

        foreach ($this->middlewares as $middleware) {
            $stack->push($middleware);
        }

        return $stack;
    }
}
