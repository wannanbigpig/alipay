# 简介

使用支付宝最新接口完成的扩展，简化对接支付宝接口的流程，方便在不同项目中快速上手使用。使用时只需要根据你所要对接的接口关注传递参数即可。

你在阅读本文之前确认你已经仔细阅读了：[**支付宝开放平台文档**](https://docs.open.alipay.com/)

欢迎 Star，欢迎 PR！

## 运行环境

* PHP 7.0+
* composer

## 安装

```text
composer require wannanbigpig/alipay -vvv
```

## 使用

```php
use WannanBigPig\Alipay\Alipay;

class PayController
{
    // 配置（包含支付宝的公共配置，日志配置，http配置等）
    protected $config = [
        'app_id'         => '*********',
        // 服务商需要设置子商户的授权token，商户自调用不需要设置此参数
        'app_auth_token' => '',
        'notify_url'     => 'http://wannanbigpig.com/notify.php',
        'return_url'     => 'http://wannanbigpig.com/return.php',
        // 支付宝公钥，可以是绝对路径（/data/***.pem）或着一行秘钥字符串
        'ali_public_key' => '******',
        'sign_type'      => 'RSA2',
        // 商户私钥，可以是绝对路径（/data/***.pem）或着一行秘钥字符串
        'private_key'    => '**********',
        'log'            => [
            // optional
            'file'     => '/data/wwwroot/alipay.dev/logs/alipay.log',
            'level'    => 'debug', // 建议生产环境等级调整为 info，开发环境为 debug
            'type'     => 'daily', // optional, 可选 single.
            'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        ],
        'http'           => [
            // optional
            'timeout'         => 5.0,
            'connect_timeout' => 5.0,
            // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        ],
        'env'            => 'dev', // optional[normal,dev],设置此参数，将进入沙箱模式，不传默认正式环境
        /**
         * 业务返回处理
         * 设置true， 返回码 10000 则正常返回成功数据，其他的则抛出业务异常
         * 捕获 BusinessException 异常 获取 raw 元素查看完整数据并做处理
         * 不设置默认 false
         */
        'business_exception' => true
    ];
    
    /**
     * 当面付 统一收单交易支付接口 pos机扫码支付
     *
     */
    public function pos()
    {
        try{
            $result = Alipay::payment($this->config)->pos([
                'out_trade_no' => Str::getRandomInt('lml', 3),
                'total_amount' => 100,
                'scene'        => "bar_code",
                'auth_code'    => "287951669891795468",
                'product_code' => "FACE_TO_FACE_PAYMENT",
                'subject'      => '商品标题',
            ]);
            // ...
        }catch (BusinessException $e){
            // business_exception 配置项开启后需要捕获该异常处理请求失败的情况
            $res = $e->raw; // 获取支付宝返回数据
            // ...
        }
    }
}
```

## 支持的方法

### 支付方法

```php
// 支付方法调用示例
Alipay::payment($this->config)->app([...]);
```

<table>
  <thead>
    <tr>
      <th style="text-align:center">method</th>
      <th style="text-align:center">&#x63CF;&#x8FF0;</th>
      <th style="text-align:center">&#x652F;&#x4ED8;&#x5B9D;API&#x6587;&#x6863;</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="text-align:center">app</td>
      <td style="text-align:center">app&#x652F;&#x4ED8;</td>
      <td style="text-align:center"><a href="https://docs.open.alipay.com/api_1/alipay.trade.app.pay/">alipay.trade.app.pay (app &#x652F;&#x4ED8;&#x63A5;&#x53E3; 2.0)</a>
      </td>
    </tr>
    <tr>
      <td style="text-align:center"> faceInit</td>
      <td style="text-align:center">&#x5237;&#x8138;&#x652F;&#x4ED8;&#xFF08;&#x5237;&#x8138;&#x521D;&#x59CB;&#x5316;&#xFF09;</td>
      <td
      style="text-align:center">
        <p><a href="https://docs.open.alipay.com/api_46/zoloz.authentication.customer.smilepay.initialize/">zoloz.authentication.customer.smilepay.initialize </a>
        </p>
        <p><a href="https://docs.open.alipay.com/api_46/zoloz.authentication.customer.smilepay.initialize/">(&#x4EBA;&#x8138;&#x521D;&#x59CB;&#x5316;&#x5524;&#x8D77; zim) </a>
        </p>
        </td>
    </tr>
    <tr>
      <td style="text-align:center">pos</td>
      <td style="text-align:center">pos&#x673A;&#x652F;&#x4ED8;</td>
      <td style="text-align:center"><a href="https://docs.open.alipay.com/api_1/alipay.trade.pay/">alipay.trade.pay (&#x7EDF;&#x4E00;&#x6536;&#x5355;&#x4EA4;&#x6613;&#x652F;&#x4ED8;&#x63A5;&#x53E3;)</a>
      </td>
    </tr>
    <tr>
      <td style="text-align:center">precreate</td>
      <td style="text-align:center">&#x9884;&#x521B;&#x5EFA;&#x8BA2;&#x5355;&#xFF08;&#x626B;&#x7801;&#x652F;&#x4ED8;&#xFF09;</td>
      <td
      style="text-align:center"><a href="https://docs.open.alipay.com/api_1/alipay.trade.precreate/">alipay.trade.precreate (&#x7EDF;&#x4E00;&#x6536;&#x5355;&#x7EBF;&#x4E0B;&#x4EA4;&#x6613;&#x9884;&#x521B;&#x5EFA;)</a>
        </td>
    </tr>
    <tr>
      <td style="text-align:center">wap</td>
      <td style="text-align:center">&#x624B;&#x673A;&#x7F51;&#x7AD9;&#x652F;&#x4ED8;</td>
      <td style="text-align:center"><a href="https://docs.open.alipay.com/api_1/alipay.trade.wap.pay/">alipay.trade.wap.pay (&#x624B;&#x673A;&#x7F51;&#x7AD9;&#x652F;&#x4ED8;&#x63A5;&#x53E3; 2.0)</a>
      </td>
    </tr>
    <tr>
      <td style="text-align:center">web</td>
      <td style="text-align:center">pc&#x7F51;&#x7AD9;&#x652F;&#x4ED8;</td>
      <td style="text-align:center"><a href="https://docs.open.alipay.com/api_1/alipay.trade.page.pay/">alipay.trade.page.pay (&#x7EDF;&#x4E00;&#x6536;&#x5355;&#x4E0B;&#x5355;&#x5E76;&#x652F;&#x4ED8;&#x9875;&#x9762;&#x63A5;&#x53E3;)</a>
      </td>
    </tr>
    <tr>
      <td style="text-align:center">miniApp</td>
      <td style="text-align:center"><a href="https://docs.alipay.com/mini/introduce/pay">&#x5C0F;&#x7A0B;&#x5E8F;&#x652F;&#x4ED8;</a>
      </td>
      <td style="text-align:center"><a href="https://docs.open.alipay.com/api_1/alipay.trade.create/">alipay.trade.create (&#x7EDF;&#x4E00;&#x6536;&#x5355;&#x4EA4;&#x6613;&#x521B;&#x5EFA;&#x63A5;&#x53E3;)</a>
      </td>
    </tr>
  </tbody>
</table>### 查询方法

```php
// 查询方法调用示例
Alipay::payment($this->config)->query->pay([...]);
```

## 详细文档

[详细开发文档](https://docs.alipay.liuml.com/)

## 代码贡献

目前只对接各类支付，资金预授权等相关接口。如果您有其它支付宝相关接口的需求，或者发现本项目中需要改进的代码，_**欢迎 Fork 并提交 PR！**_

## LICENSE

MIT

