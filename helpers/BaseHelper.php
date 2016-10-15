<?php

namespace byiitrix\helpers;

/**
 * Class BaseHelper
 * @package byiitrix\helpers
 */
abstract class BaseHelper
{
    private static $cache = [];

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

    /**
     * @param string       $method
     * @param string|array $cacheKey
     * @param string|array $cacheValue
     */
    public static function setCache($method, $cacheKey, $cacheValue)
    {
        $key                 = self::generateCacheKey($method, $cacheKey);
        static::$cache[$key] = $cacheValue;
    }

    /**
     * @param mixed $cacheKey
     *
     * @return null|mixed
     */
    protected static function checkCache($method, $cacheKey)
    {
        $key = self::generateCacheKey($method, $cacheKey);

        return isset(static::$cache[$key]) ? static::$cache[$key] : NULL;
    }

    /**
     * @param string $method
     * @param mixed $cacheKey
     *
     * @return string
     */
    private static function generateCacheKey($method, $cacheKey)
    {
        return $method . serialize($cacheKey);
    }
}
