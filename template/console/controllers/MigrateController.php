<?php

namespace console\controllers;

class MigrateController extends \yii\console\controllers\MigrateController
{
    public function beforeAction($action)
    {
        \Yii::$app->bitrix->flushCache();
        \Yii::$app->getCache()->flush();

        return parent::beforeAction($action);
    }

    public function afterAction($action, $result)
    {
        \Yii::$app->bitrix->flushCache();
        \Yii::$app->getCache()->flush();

        return parent::afterAction($action, $result);
    }
}
