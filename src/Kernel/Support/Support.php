<?php
/*
 * This file is part of the wannanbigpig/alipay.
 *
 * (c) wannanbigpig <liuml0211@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WannanBigPig\Alipay\Kernel\Support;

use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use WannanBigPig\Alipay\Kernel\Http\Response;
use WannanBigPig\Alipay\Kernel\ServiceContainer;
use WannanBigPig\Alipay\Kernel\Traits\Helpers;
use WannanBigPig\Supports\Traits\HttpRequest;
use WannanBigPig\Supports\Traits\ResponseCastable;

class Support
{
    use Helpers, ResponseCastable, HttpRequest {
        HttpRequest::request as performRequest;
    }

    /**
     * @var \WannanBigPig\Alipay\Kernel\ServiceContainer
     */
    protected $app;

    /**
     * Support constructor.
     *
     * @param \WannanBigPig\Alipay\Kernel\ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;

        $this->setHttpClient($this->app['http_client']);
    }

    /**
     * request.
     *
     * @param        $endpoint
     * @param array  $params
     * @param string $method
     * @param array  $options
     * @param bool   $returnResponse
     *
     * @return array|object|\Psr\Http\Message\ResponseInterface|\WannanBigPig\Supports\Collection|\WannanBigPig\Supports\Http\Response
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \WannanBigPig\Supports\Exceptions\InvalidArgumentException
     */
    public function request($endpoint, $params = [], $method = 'POST', array $options = [], $returnResponse = false)
    {
        // Get api system parameters
        $sysParams = $this->app->apiCommonConfig($endpoint);
        // Filter system parameters
        $sysParams = array_filter($sysParams, function ($value) {
            return !($this->checkEmpty($value));
        });
        $params = $this->json($params);
        // Set the signature
        $sysParams['sign'] = $this->generateSign(array_merge($sysParams, $params));
        // Set log middleware to record data, Log request and response data to the log file info level
        $this->pushMiddleware($this->logMiddleware(), 'log');
        // Set http parameter options
        $options = array_merge([
            'form_params' => $params,
            'headers' => [
                'content-type' => 'application/x-www-form-urlencoded',
                'charset' => $sysParams['charset'],
            ],
        ], $options);

        $response = $this->performRequest($method, '?'.http_build_query($sysParams), $options);

        $result = $returnResponse ? $response : $this->castResponseToType($response, new Response(), $this->app->config->get('response_type'));

        return $result;
    }

    /**
     * Log the request.
     *
     * @return \Closure
     */
    protected function logMiddleware()
    {
        $formatter = new MessageFormatter($this->app['config']['http.log_template'] ?? MessageFormatter::DEBUG);

        return Middleware::log($this->app['logger']->getLogger(), $formatter);
    }

    /**
     * json.
     *
     * @param array $data
     *
     * @return array
     */
    protected function json(array $data)
    {
        if (isset($data['biz_content'])) {
            $data['biz_content'] = \GuzzleHttp\json_encode($data['biz_content'], JSON_UNESCAPED_UNICODE);
        }

        return $data;
    }
}
