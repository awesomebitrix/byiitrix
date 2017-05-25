<?php

namespace common\bitrix;

/**
 * Class Application
 * @package common\bitrix
 * @property \common\bitrix\ServiceLocator $bitrix
 */
class Application extends \yii\base\Application
{
    /**
     * @param \yii\base\Request $request the request to be handled
     *
     * @return \yii\base\Response the resulting response
     */
    public function handleRequest($request)
    {
        return $this->getResponse();
    }

    protected function bootstrap()
    {
        parent::bootstrap();

        \Yii::$block        = \Yii::$app->bitrix->block;
        \Yii::$element      = \Yii::$app->bitrix->element;
        \Yii::$property     = \Yii::$app->bitrix->property;
        \Yii::$propertyEnum = \Yii::$app->bitrix->propertyEnum;
        \Yii::$section      = \Yii::$app->bitrix->section;
    }

    /**
     * @return array
     */
    public function coreComponents()
    {
        return array_merge(parent::coreComponents(), [
            'errorHandler' => ['class' => ErrorHandler::class],
            'response'     => ['class' => Response::class],
            'request'      => ['class' => Request::class],
        ]);
    }

    public function run()
    {
        $this->state = self::STATE_BEFORE_REQUEST;
        $this->trigger(self::EVENT_BEFORE_REQUEST);

        $this->state = self::STATE_HANDLING_REQUEST;
        $this->handleRequest($this->getRequest());

        \AddEventHandler('main', 'OnAfterEpilog', function () {
            $this->state = self::STATE_AFTER_REQUEST;
            $this->trigger(self::EVENT_AFTER_REQUEST);

            $this->state = self::STATE_SENDING_RESPONSE;
            $this->getResponse()->send();

            $this->state = self::STATE_END;
        });
    }
}
