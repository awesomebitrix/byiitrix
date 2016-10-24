<?php

namespace byiitrix\helpers;

/**
 * Class BaseHelper
 * @package byiitrix\helpers
 */
abstract class BaseHelper
{
    final private function __construct()
    {
    }

    /**
     * @return static
     */
    public static function instance()
    {
        static $instances;

        if( $instances === NULL ) {
            $instances = [];
        }

        if( array_key_exists(static::class, $instances) === false ) {
            $instances[static::class] = new static();
        }

        return $instances[static::class];
    }

    private static $cache = [];

    /**
     * @param \Closure $callable
     *
     * @return mixed
     */
    protected static function cache($callable)
    {
        $key = serialize(debug_backtrace()[1]);

        if( array_key_exists($key, self::$cache) === false ) {
            self::$cache[$key] = call_user_func($callable);
        }

        return self::$cache[$key];
    }
}
