<?php
/*
 * This file is part of the wannanbigpig/alipay-sdk-php.
 *
 * (c) wannanbigpig <liuml0211@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAlipay\MiniProgram;

use EasyAlipay\Kernel\ServiceContainer;

/**
 * Class Application
 *
 * @author   liuml  <liumenglei0211@163.com>
 * @DateTime 2019-07-18  16:13
 *
 * @property \EasyAlipay\MiniProgram\Members\Client $members
 * @property \EasyAlipay\MiniProgram\QrCode\Client  $qrCode
 * @property \EasyAlipay\MiniProgram\Version\Client $version
 *
 * @method mixed getBaseInfo()
 * @method mixed updateBaseInfo(array $params)
 * @method mixed getUsageTemplateList(array $params)
 * @method mixed createSafeDomain(string $safeDomain)
 * @method mixed deleteSafeDomain(string $safeDomain)
 * @method mixed contentRiskDetect(string $content)
 * @method mixed getCategoryList()
 * @method mixed faceAuthenticationResultsQuery()
 * @method mixed getAccessToken(string $code, string $grantType = 'authorization_code')
 * @method mixed getAuthorizationUrl(string $redirectUri, string $scope = 'auth_base', string $state = null)
 * @method mixed getUserInfo(string $authToken)
 * @method mixed loginAuthorization(string $state, string $scopes = 'auth_base', string $returnUrl = null)
 * @method mixed certifyInitialize(array $params)
 * @method mixed certifyStart(string $certifyId)
 * @method mixed getCertifyStatus(string $certifyId)
 */
class Application extends ServiceContainer
{

    /**
     * @var array
     */
    protected $providers = [
        'base' => Base\Client::class,
        'members' => Base\Client::class,
        'qrCode' => Base\Client::class,
        'version' => Base\Client::class,
        'auth' => \EasyAlipay\BaseService\Auth\Client::class,
        'user' => \EasyAlipay\BaseService\User\Client::class,
    ];

    /**
     * __call.
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this['base'], $name], $arguments);
    }
}
