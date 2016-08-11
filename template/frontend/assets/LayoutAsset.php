<?php

namespace frontend\assets;

use common\web\AssetBundle;

class LayoutAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl  = '@web';
    public $css      = [
        'css/layout.css',
    ];
    public $depends  = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
