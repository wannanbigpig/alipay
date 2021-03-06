<?php
/*
 * This file is part of the wannanbigpig/alipay-sdk-php.
 *
 * (c) wannanbigpig <liuml0211@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAlipay\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use EasyAlipay\Kernel\ServiceContainer;

/**
 * class TestCase.
 */
class TestCase extends BaseTestCase
{
    public function mockApiClient($name, $methods = [], ServiceContainer $app = null)
    {
        $methods = implode(',', array_merge([
            'request',
        ], (array)$methods));

        $client = \Mockery::mock($name."[{$methods}]", [
                $app ?? \Mockery::mock(ServiceContainer::class),
            ]
        )->shouldAllowMockingProtectedMethods();
        $client->allows()->registerHttpMiddlewares()->andReturnNull();

        return $client;
    }
}
