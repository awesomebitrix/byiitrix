<?php

namespace common\web;

use Yii;

class AssetBundle extends \yii\web\AssetBundle
{
    public function init()
    {
        parent::init();

        $this->js  = array_map([$this, 'appendTimestamp'], $this->js);
        $this->css = array_map([$this, 'appendTimestamp'], $this->css);
    }

    public function appendTimestamp($file)
    {
        if( strncmp($file, '/', 1) === 0 ) {
            $path = Yii::getAlias('@webroot') . $file;

            if( file_exists($path) && ($timestamp = @filemtime($path)) > 0 ) {
                return $file . (strpos($file, '?') === false ? '?' : '&') . 'v=' . $timestamp;
            }
        }

        return $file;
    }
}
