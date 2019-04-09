<?php
/**
 * AppTrade.php
 *
 * Created by PhpStorm.
 *
 * author: liuml  <liumenglei0211@163.com>
 * DateTime: 2019-04-04  16:36
 */

namespace WannanBigPig\Alipay\Payment\Trade;

use Symfony\Component\HttpFoundation\Response;
use WannanBigPig\Alipay\Kernel\Support\Support;
use WannanBigPig\Alipay\Payment\PayInterface;
use WannanBigPig\Alipay\Kernel;
use WannanBigPig\Supports\Exceptions\InvalidArgumentException;

class AppTrade implements PayInterface
{

    /**
     * method
     *
     * @var string
     */
    private $method = 'alipay.trade.app.pay';

    /**
     * pay
     *
     * @param       $endpoint
     * @param array $payload
     *
     * @return Response
     *
     * @throws InvalidArgumentException
     *
     * @link https://docs.open.alipay.com/api_1/alipay.trade.app.pay/
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-08  17:42
     */
    public function pay($endpoint, array $payload): Response
    {
        $payload['method'] = $this->method;

        $payload['sign'] = Support::generateSign($payload);

        return Response::create(http_build_query($payload));
    }
}