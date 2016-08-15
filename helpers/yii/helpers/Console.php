<?php

namespace yii\helpers;

use Yii;
use yii\console\Exception;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Console extends BaseConsole
{
    /**
     * @param string   $title
     * @param callable $callback
     * @param array    $params
     *
     * @return mixed
     */
    public static function timeout($title, $callback, $params = [])
    {
        $start = microtime(true);

        echo " > start {$title}..." . PHP_EOL;

        $result = call_user_func_array($callback, $params);
        $time   = sprintf('%.3f', microtime(true) - $start) . ' seconds';

        if( Yii::$app->controller->isColorEnabled() ) {
            $time = Console::ansiFormat($time, [Console::FG_GREEN]);
        }

        echo " > done {$title} in {$time}" . PHP_EOL;

        return $result;
    }

    public static function printError($error)
    {
        return fwrite(\STDERR, rtrim(Console::ansiFormat($error, [self::FG_RED])) . PHP_EOL);
    }
}
