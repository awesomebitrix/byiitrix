<?php

namespace frontend\controllers;

use common\models\api\LoginTokenApi;
use frontend\models\RegisterPartnershipForm;
use Yii;
use common\web\Controller;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
