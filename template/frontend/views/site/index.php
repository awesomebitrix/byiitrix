<?php
/**
 * @var \common\web\View $this
 */

$title = 'Congratulations';

if( Yii::$app->getUser()->getIsGuest() === false ) {
    $user = Yii::$app->getUser()->getIdentity();
    $title .= ', ' . $user->NAME . ' ' . $user->LAST_NAME;
}
?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?= \yii\helpers\Html::encode(trim($title)); ?>!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>
</div>
