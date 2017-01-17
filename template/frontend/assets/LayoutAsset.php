<?php

namespace frontend\assets;

use yii\web\YiiAsset;
use yii\bootstrap\BootstrapAsset;
use common\web\AssetBundle;

class LayoutAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl  = '@web';
    public $css      = [
        'css/layout.css',
    ];
    public $depends  = [
        YiiAsset::class,
        BootstrapAsset::class,
    ];
}
