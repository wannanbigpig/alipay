<?php
/*
 * This file is part of the wannanbigpig/alipay-sdk-php.
 *
 * (c) wannanbigpig <liuml0211@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAlipay;

use EasyAlipay\Kernel\Exceptions\ApplicationException;
use WannanBigPig\Supports\Str;

/**
 * Class Alipay
 *
 * @method static \EasyAlipay\Payment\Application payment(array $config = [])
 * @method static \EasyAlipay\Tripartite\Application tripartite(array $config = [])
 * @method static \EasyAlipay\MiniProgram\Application miniProgram(array $config = [])
 * @method static \EasyAlipay\BaseService\Application baseService(array $config = [])
 *
 * @author   liuml  <liumenglei0211@163.com>
 * @DateTime 2019-07-26  18:18
 */
class Alipay
{
    protected static $config = [];

    /**
     * make.
     *
     * @param string $name
     * @param array  $config
     *
     * @return mixed
     *
     * @throws \EasyAlipay\Kernel\Exceptions\ApplicationException
     */
    public static function make(string $name, array $config = [])
    {
        $namespace = Str::studly($name);
        $application = __NAMESPACE__."\\{$namespace}\\Application";

        if (class_exists($application)) {
            $config = array_replace_recursive(self::$config, $config);

            // Instantiation application
            return new $application($config);
        }
        throw new ApplicationException("Application [{$name}] does not exist");
    }

    /**
     * Dynamically pass methods to the application.
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     *
     * @throws \EasyAlipay\Kernel\Exceptions\ApplicationException
     */
    public static function __callStatic($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }

    /**
     * @static  initialize.
     *
     * @param array $config
     *
     * @return \EasyAlipay\Alipay
     */
    public static function initialize(array $config): self
    {
        self::$config = $config;

        return new static();
    }

    /**
     * __call.
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     *
     * @throws \EasyAlipay\Kernel\Exceptions\ApplicationException
     */
    public function __call($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }
}
