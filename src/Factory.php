<?php
declare (strict_types=1);
/**
 * This file is part of the jksusu/wechatsm.
 *
 * @link     https://github.com/jksusu/wechatsm
 * @license  https://github.com/jksusu/wechatsm/blob/master/LICENSE
 */

namespace Wechatsm;

/**
 * Class Factory.
 *
 * @method static \Wechatsm\Apply\Application      apply(array $config)
 */
class Factory
{
    public static function make($name, array $config)
    {
        $namespace = "\\Wechatsm\\$name\\Application";
        return new $namespace($config);
    }

    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
        return self::make($name, ...$arguments);
    }
}