<?php

namespace common\console;

use yii\console\controllers\AssetController;
use yii\console\controllers\CacheController;
use yii\console\controllers\FixtureController;
use yii\console\controllers\HelpController;
use yii\console\controllers\MessageController;
use yii\console\controllers\ServeController;
use console\controllers\MigrateController;

class Application extends \yii\console\Application
{
    public function coreCommands()
    {
        return [
            'asset'   => AssetController::class,
            'cache'   => CacheController::class,
            'fixture' => FixtureController::class,
            'help'    => HelpController::class,
            'message' => MessageController::class,
            'migrate' => MigrateController::class,
            'serve'   => ServeController::class,
        ];
    }
}
