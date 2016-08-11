<?php
/**
 * @var \common\web\View $this
 */

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl'   => Yii::$app->getHomeUrl(),
    'options'    => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);

echo Nav::widget([
    'options'      => ['class' => 'navbar-nav navbar-right'],
    'items'        => [
        [
            'label' => Yii::t('app', 'Main'),
            'url'   => ['/site/index'],
        ],
        [
            'label' => Yii::t('app', 'About'),
            'url'   => ['/site/about'],
        ],
        [
            'label' => Yii::t('app', 'Contacts'),
            'url'   => ['/site/contact'],
        ],
        [
            'label'       => Yii::t('app', 'Sign out'),
            'url'         => ['/auth/sign-out'],
            'linkOptions' => ['data-method' => 'post'],
        ],
    ],
    'encodeLabels' => false,
]);

NavBar::end();
