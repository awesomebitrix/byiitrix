<?php

namespace common\widgets;

class ActiveForm extends \yii\bootstrap\ActiveForm
{
    public $enableAjaxValidation   = true;
    public $enableClientValidation = false;
    public $layout                 = 'horizontal';
    public $fieldConfig            = [
        'template'                   => "{label}\n{beginWrapper}\n{input}\n{error}\n{hint}\n{endWrapper}",
        'horizontalCheckboxTemplate' => "{beginWrapper}\n<div class=\"checkbox\">\n{beginLabel}\n{input}\n{labelTitle}\n{endLabel}\n</div>\n{error}\n{hint}\n{endWrapper}",
        'horizontalCssClasses'       => [
            'hint' => '',
        ],
    ];
}
