<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class HelpController extends \yii\console\controllers\HelpController
{
    public function actionList()
    {
        $commands = $this->getCommandDescriptions();

        foreach( $commands as $command => $description ) {
            $result = Yii::$app->createController($command);

            if( $result === false || !($result[0] instanceof Controller) ) {
                continue;
            }

            /**
             * @var $controller Controller
             */
            list($controller, $actionID) = $result;
            $actions = $this->getActions($controller);

            if( !empty($actions) ) {
                $prefix = $controller->getUniqueId();

                foreach( $actions as $action ) {
                    $this->stdout("$prefix/$action\n");
                }
            }
        }
    }
}