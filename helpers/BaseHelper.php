<?php

namespace byiitrix\helpers;

class BaseHelper
{
    final private function __construct()
    {
    }

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
}
