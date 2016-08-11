<?php

namespace common\web;

use Yii;

class View extends \yii\web\View
{
    public function registerJsFile($url, $options = [], $key = NULL)
    {
        if( Yii::$app->getAssetManager()->appendTimestamp === true ) {
            $url = $this->appendTimestamp($url);
        }

        parent::registerJsFile($this->appendTimestamp($url), $options, $key);
    }

    public function registerCssFile($url, $options = [], $key = NULL)
    {
        if( Yii::$app->getAssetManager()->appendTimestamp === true ) {
            $url = $this->appendTimestamp($url);
        }

        parent::registerCssFile($this->appendTimestamp($url), $options, $key);
    }

    public function appendTimestamp($file)
    {
        if( strpos($file, '/') === 0 ) {
            $path = Yii::getAlias('@webroot') . $file;

            if( file_exists($path) && ($timestamp = @filemtime($path)) > 0 ) {
                return $file . (strpos($file, '?') === false ? '?' : '&') . 'v=' . $timestamp;
            }
        }

        return $file;
    }

    /**
     * Try to append css and js files by path mask from @webroot, can used placeholders
     *
     * #ACTION_ID#     - action id
     * #CONTROLLER_ID# - controller id
     * #MODULE_ID#     - module id, if exists
     * #MODULE_PATH#   - path of chaining modules id, like "cabinet/statistic/daily"
     * #EXTENSION#     - file extension, trying to append, like "css" or "js"
     *
     * @param string $mask
     *
     * @throws \yii\base\InvalidParamException
     */
    public function registerRelatedFiles($mask)
    {
        $dir = Yii::getAlias('@webroot');

        $module       = Yii::$app->module;
        $actionID     = Yii::$app->controller->action->id;
        $controllerID = Yii::$app->controller->id;
        $moduleID     = $module !== NULL ? $module->id : NULL;
        $moduleIDList = [];

        while( $module !== NULL ) {
            $moduleIDList[] = $module->id;
            $module         = $module->module;
        }

        $modulePath = implode('/', $moduleIDList);

        $script = preg_replace('#/{2,}#', '/', strtr($mask, [
            '#ACTION_ID#'     => $actionID,
            '#CONTROLLER_ID#' => $controllerID,
            '#MODULE_ID#'     => $moduleID,
            '#MODULE_PATH#'   => $modulePath,
            '#EXTENSION#'     => 'js',
        ]));

        $file = $dir . $script;

        if( file_exists($file) ) {
            $this->registerJsFile($script, ['depends' => array_keys($this->assetBundles)]);
        }

        $style = preg_replace('#/{2,}#', '/', strtr($mask, [
            '#ACTION_ID#'     => $actionID,
            '#CONTROLLER_ID#' => $controllerID,
            '#MODULE_ID#'     => $moduleID,
            '#MODULE_PATH#'   => $modulePath,
            '#EXTENSION#'     => 'css',
        ]));
        $file  = $dir . $style;

        if( file_exists($file) ) {
            $this->registerCssFile($style, ['depends' => array_keys($this->assetBundles)]);
        }
    }
}
