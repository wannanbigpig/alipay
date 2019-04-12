<?php
/**
 * OtherTest.php
 *
 * Created by PhpStorm.
 *
 * author: liuml  <liumenglei0211@163.com>
 * DateTime: 2019-04-10  17:38
 */

namespace WannanBigPig\Alipay\Tests\payment;

use PHPUnit\Framework\TestCase;
use WannanBigPig\Alipay\Alipay;
use WannanBigPig\Alipay\Kernel\Exceptions\SignException;
use WannanBigPig\Alipay\Payment\Application;
use WannanBigPig\Supports\Exceptions;
use WannanBigPig\Supports\Str;

class PaymentTest extends TestCase
{
    /**
     * testAlipay
     *
     * @return Application
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-11  10:49
     */
    public function testAlipay(): Application
    {
        $alipay = Alipay::payment([
            'app_id'         => '2016092600598145',
            'app_auth_token' => '',
            'notify_url'     => 'http://wannanbigpig.cn/notify.php',
            'return_url'     => 'http://wannanbigpig.cn/return.php',
            'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApN8Lzs5UAIel8MJRFCgxPf0fZIjkT+qAdodHvxSeXba7Dy5DKFScG2Tre2Cvr99H3Jf516X3n1N+BxRgq3lgnG6q79rGZjRWSeOWwkDUmJ4/cVgw6G5Y+JesAbYdGKxQESXUIA0/xEQm8klt2SE7gazm4O1jduKhfy53PCImRVrLW5jXlUykyblOIXQy4gzVo7UhSeBafRBR3DhO979yztcJJc7JWXui/bHm3Axm68Da4C1Fk44OMgD4VEU0kS8aeE3nrWX/JBMhduZZx4JTSs2299uMncEI6NsNKLgovuffspcAqUO6hwU3J7ygSdVpBjbULLkiL6DSOVopZOn6FQIDAQAB',
            // 加密方式： **RSA2**
            'private_key'    => 'MIIEpQIBAAKCAQEA5faX0bBSs+UgsMvbfV+Hop1aoduJ0X9Qw9AXhWZ2XX2SsyFvE94V9dWxPnGYi54tnVYrxnAsd4fT9njFfO5NaldUghC5X/3C6QymuixPFbEb5bGugxmjdhy0BO9mI6hjnJvio/oFabvZWD8doxDi1MqpM8eguo+wqwd1GnxuZxrYvNsPyfnPRC6o3YNIBkfAH/rt71MUH8H4uDx4SkRE+aPfOZtartUM7++z464wM+BA/sD03pRMcS3nMWHWYeYdn9CHL128DsOTOKKlJB11xLfOhjfK0M0RpCG7jEBiBEvc+cl+9NqvqhT9BsgMPGWinUlDIrLF1jsxKcdlf+PMQwIDAQABAoIBAQDDgJUpa6Gj1tOn+merep+xG92FZUMRnA9pqWuVubo/WRZyu6XXWiOJUBbTY3ewmtVkwXGNzqe/JvaIv7wFrgKauYva16UBepdN0bec5zaE1oFFEX2vbwiMzXIuD+jhv7KP3eccSN55OX5Zi68ChsSQ64pVvw1iDe7AOCLSVZ72fzvMm4PyJIYuAQ3G3UkP9peNGT59fI7PV4xe078KLQwmuxM26Jzrf2n6/rYjwinW+g8626csSic0/+DBo99Ru2BetClfvZ6p4Qk66UtUwhkuDd6B2K5o0F9uQD1UcNdvuU/VOIRZ6Co8B7BOFggHQxZt8JU7NJT18OcA0mDramFhAoGBAPccLwmcVhOEH1F5YDcVRzgaRH5DI7dh9qkcW0c3OU4mMRuZMVCbwwyCGckTI3xY1BQOx/iKoXMq7Gm5brhaXpc6YPNKAXeMYdyvqYOBWIVCctNYp/6ChoiW90kb+qLfZxIkWvDz6fgmNCENb1dVCUEk1gyt6q9DxQgNBM1cLboFAoGBAO48fdqOtwLrkPy/J1lLIkb7EU2yIDLDrtflZRH8nsG68+dmuTwjXvKKuV6b3c4A7U6j3uDVVIRB53r/sYlyY/O9wzOSjLQSWFy8YsNcY8xIhvov03fUUY/EHHG3+h4yglHxpFL/BsoO/3od0WQtdk49zrTw3iRhqC8Fvk3TRRenAoGBAIifMnpzzztXDyGyo7mQsCGalEfiwvp+1StGnEjRhYNppjkGB7fzhnGB9NOxGyuCyS6VxYXqz7ym/LKvbUHL5QRjqHqabhk0ql6jWGt2tgRnaqjjGW6jp9IY9XucVoR6U7g6FXWmxbMHHEcx8F8uisFTpmy4M0rXgzYiTIdl8XopAoGBAN9lzCJ5d3X+jbvkSDK8eM0Uu9oesYDI7Ji5HHisafaCqBqSwhp5lJxdp4vnHywAxIbctbAhe5p17mnxgXrA0KeMh5JB1z04grGbWgWWCmNSk3fiByuz5jOpE38zpRBSDtBmhs/pI2WwgLLzaRnGY8zkuoQD5ls5VCub+CMkfQK7AoGAa0RhlQarUWymV9CemB2hj219bB9mxvhdiYgnO4FhYpBdgQZRY+EE1t88k2rbnDT0dWEt34+GqDRbI+nvUvgc8dAwlV/swgLc2glk01gK33yCufkRIJwcXb9Gb8pxB2PGLA/EGAVwluyDNWc+C3xdnCLTmFyhY0VBS4aYusOkjIc=',
            'log'            => [
                // optional
                'file'     => './logs/alipay.log',
                'level'    => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
                'type'     => 'single', // optional, 可选 daily.
                'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
            ],
            'http'           => [
                // optional
                'timeout'         => 5.0,
                'connect_timeout' => 5.0,
            ],
            'env'            => 'dev', // optional,设置此参数，将进入沙箱模式,默认为正式环境
        ]);

        $this->assertNotEmpty($alipay);

        return $alipay;
    }

    /**
     * testCancel
     * 支付取消
     *
     * @param Application $alipay
     *
     * @depends  testAlipay
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-12  11:04
     */
    public function testCancel(Application $alipay)
    {
        $result = $alipay->cancel([
            'out_trade_no'   => 'gpwKLbarfkdvC9A1SRjqFc',
            'trade_no'       => '2019041122001491681000007119',
            'out_request_no' => 'gpwKLbarfkdvC9A1SRjqFc',
        ]);
        echo $result;
        $this->assertNotEmpty($result);
    }

    /**
     * testClose
     * 关闭订单
     *
     * @param Application $alipay
     *
     * @depends  testAlipay
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-12  11:40
     */
    public function testClose(Application $alipay)
    {
        $result = $alipay->close([
            'out_trade_no'   => 'gpwKLbarfkdvC9A1SRjqFc',
            'trade_no'       => '2019041122001491681000007119',
            'out_request_no' => 'gpwKLbarfkdvC9A1SRjqFc',
        ]);
        echo $result;
        $this->assertNotEmpty($result);
    }

    /**
     * testQuery
     * 订单查询（支付，退款）
     *
     * @param Application $alipay
     *
     * @throws Exceptions\BusinessException
     * @throws Exceptions\InvalidArgumentException
     * @throws SignException
     *
     * @depends  testAlipay
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-12  11:43
     */
    public function testQuery(Application $alipay)
    {
        // find 第二个参数不传默认pay （所有支付宝支付订单的查询）传入refund （退款订单查询）
        $result = $alipay->query([
            'out_trade_no'   => 'gpwKLbarfkdvC9A1SRjqFc',
            'trade_no'       => '2019041122001491681000007119',
            'out_request_no' => 'gpwKLbarfkdvC9A1SRjqFc',
        ])->pay();
        // pay() 支付订单查询
        // refund() 退款订单查询

        echo $result;
        $this->assertNotEmpty($result);
    }

    /**
     * testApp
     * APP支付
     *
     * @param Application $alipay
     *
     * @depends  testAlipay
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-10  17:46
     */
    public function testApp(Application $alipay)
    {
        $result = $alipay->app([
            'out_trade_no' => time(),
            'total_amount' => '0.01',
            'subject'      => 'mac X pro 2080',
            'http_method'  => 'post',
        ]);
        $result->send();
        $this->assertNotEmpty($result);
    }

    /**
     * testFaceInit
     * 人脸支付识别初始化
     *
     * @param Application $alipay
     *
     * @depends  testAlipay
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-10  18:06
     */
    public function testFaceInit(Application $alipay)
    {
        $result = $alipay->faceInit([
            'zimmetainfo' => '{"apdidToken": "设备指纹", "appName": "应用名称", "appVersion": "应用版本", "bioMetaInfo": "生物信息如 2.3.0:3,-4"}',
        ]);
        echo $result;
        $this->assertNotEmpty($result);
    }

    /**
     * testMiniApp
     * 小程序支付 订单预创建接口
     *
     * @param Application $alipay
     *
     * @depends  testAlipay
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-10  18:11
     */
    public function testMiniApp(Application $alipay)
    {
        $result = $alipay->miniApp([
            'out_trade_no' => Str::getRandomInt('lml', 3),
            'total_amount' => 100,
            'seller_id'    => "2088102177302492",
            'subject'      => 'mac X pro 2080',
            'body'         => 'mac X pro 2080',
        ]);

        echo $result;
        $this->assertNotEmpty($result);
    }

    /**
     * testPos
     * pos机等扫码支付
     *
     * @param Application $alipay
     *
     * @depends  testAlipay
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-11  09:31
     */
    public function testPos(Application $alipay)
    {
        $result = $alipay->pos([
            'out_trade_no' => Str::getRandomInt('lml', 3),
            'total_amount' => 100,
            'scene'        => "bar_code",
            'auth_code'    => "281246173186124919",
            'product_code' => "FACE_TO_FACE_PAYMENT",
            'subject'      => 'mac Xpro',
        ]);
        echo $result;
        $this->assertNotEmpty($result);
    }

    /**
     * testPrecreate
     * 预创建订单，生成二维码，被扫
     *
     * @param Application $alipay
     *
     * @depends  testAlipay
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-11  11:41
     */
    public function testPrecreate(Application $alipay)
    {
        $result = $alipay->precreate([
            'out_trade_no' => Str::getRandomInt('lml', 3),
            'total_amount' => 100,
            'subject'      => 'mac Xpro',
        ]);

        echo $result;
        $this->assertNotEmpty($result);
    }

    /**
     * testRefund
     * 支付订单退款
     *
     * @param Application $alipay
     *
     * @depends  testAlipay
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-12  11:47
     */
    public function testRefund(Application $alipay)
    {
        $result = $alipay->refund([
            'out_trade_no'  => 'wrKsPGxfN6uaR8z0Lj3Zbv',
            'trade_no'      => '2019041022001491681000005830',
            'refund_amount' => '100',
        ]);

        echo $result;
        $this->assertNotEmpty($result);
    }

    /**
     * testWap
     * 手机网站支付
     *
     * @param Application $alipay
     *
     * @depends  testAlipay
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-11  11:45
     */
    public function testWap(Application $alipay)
    {
        $result = $alipay->wap([
            'out_trade_no' => Str::getRandomString(22),
            'total_amount' => 100,
            'subject'      => 'mac Xpro',
            'quit_url'     => 'http://wannanbigpig.com',
            'product_code' => 'QUICK_WAP_WAY',
            'http_method'  => 'get',
        ]);

        echo $result;
        $this->assertNotEmpty($result);
    }

    /**
     * testWeb
     * PC场景支付
     *
     * @param Application $alipay
     *
     * @depends  testAlipay
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-11  11:58
     */
    public function testWeb(Application $alipay)
    {
        $result = $alipay->web([
            'out_trade_no' => Str::getRandomString(22),
            'total_amount' => 100,
            'subject'      => 'mac Xpro',
            'quit_url'     => 'http://wannanbigpig.com',
            'product_code' => 'FAST_INSTANT_TRADE_PAY',
        ]);

        echo $result;
        $this->assertNotEmpty($result);
    }

    /**
     * testdownload
     * 下载对账单
     *
     * @param Application $alipay
     *
     * @depends  testAlipay
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-12  11:47
     */
    public function testdownload(Application $alipay)
    {
        $result = $alipay->download([
            'bill_type' => 'trade',
            'bill_date' => '2019-04-09',
        ]);

        echo $result;
        $this->assertNotEmpty($result);
    }

    /**
     * testException
     *
     * @param Application $alipay
     *
     * @depends  testAlipay
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-12  11:49
     */
    public function testException(Application $alipay)
    {
        $this->expectExceptionMessage("APPLICATION_ERROR: The func method doesn't exist");
        $result = $alipay->func([]);

    }

    /**
     * testFooGateway
     *
     * @param Application $alipay
     *
     * @depends                  testAlipay
     * @expectedException \WannanBigPig\Supports\Exceptions\ApplicationException
     * @expectedExceptionMessage APPLICATION_ERROR: The foo method doesn't exist
     *
     * @author                   liuml  <liumenglei0211@163.com>
     * @DateTime                 2019-04-12  11:54
     */
    public function testFooGateway(Application $alipay)
    {
        $alipay->foo([]);
    }

}