<?php

namespace common\web;

/**
 * Class Application
 * @package common\web
 *
 * @property \common\web\User     $user
 * @property \common\web\View     $view
 * @property \common\web\Request  $request
 * @property \common\web\Response $response
 *
 * @method \common\web\User      getUser()
 * @method \common\web\View      getView()
 * @method \common\web\Request   getRequest()
 * @method \common\web\Response  getResponse()
 */
class Application extends \yii\web\Application
{
}
