<?php
/**
 * @var \common\web\View $this
 * @var string           $content
 */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

\frontend\assets\LayoutAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags(); ?>
    <title><?= Html::encode($this->title ? : Yii::$app->name); ?></title>
    <link rel="icon" href="/favicon.ico">
    <?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody(); ?>

<div class="wrap">
    <?= $this->render('partial/navigation'); ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]); ?>
        <?= Alert::widget(); ?>
        <?= $content; ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y'); ?></p>

        <p class="pull-right"><?= Yii::powered(); ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
