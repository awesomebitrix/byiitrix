<?php

namespace byiitrix\helpers\yii;

use yii\helpers\BaseArrayHelper;

class ArrayHelper extends BaseArrayHelper
{
    public static function isEmpty($value)
    {
        return $value === NULL || $value === '' || $value === [];
    }

    /**
     * @param array|object          $array
     * @param array|\Closure|string $key
     * @param null                  $default
     *
     * @return mixed
     */
    public static function getObjValue($array, $key, $default = NULL)
    {
        if( $key instanceof \Closure ) {
            return $key($array, $default);
        }

        if( is_array($key) ) {
            $lastKey = array_pop($key);
            foreach( $key as $keyPart ) {
                $array = static::getObjValue($array, $keyPart);
            }
            $key = $lastKey;
        }

        if( is_array($array) && array_key_exists($key, $array) ) {
            return $array[$key];
        }

        if( ($pos = strrpos($key, '.')) !== false ) {
            $array = static::getObjValue($array, substr($key, 0, $pos), $default);
            $key   = substr($key, $pos + 1);
        }

        if( is_object($array) && isset($array[$key]) ) {
            return $array->$key;
        } elseif( is_array($array) ) {
            return array_key_exists($key, $array) ? $array[$key] : $default;
        } else {
            return $default;
        }
    }
}
