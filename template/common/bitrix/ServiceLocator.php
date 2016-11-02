<?php

namespace common\bitrix;

use yii\helpers\ArrayHelper;

/**
 * Class ServiceLocator
 * @package common\bitrix\di
 * @property \common\bitrix\components\Block        $block
 * @property \common\bitrix\components\Property     $property
 * @property \common\bitrix\components\PropertyEnum $propertyEnum
 * @property \common\bitrix\components\Element      $element
 * @property \common\bitrix\components\Section      $section
 */
class ServiceLocator extends \yii\di\ServiceLocator
{
    public function __construct(array $config = [])
    {
        $config['components'] = ArrayHelper::merge([
            'block'        => [
                'class' => 'common\bitrix\components\Block',
            ],
            'property'     => [
                'class' => 'common\bitrix\components\Property',
            ],
            'propertyEnum' => [
                'class' => 'common\bitrix\components\PropertyEnum',
            ],
            'element'      => [
                'class' => 'common\bitrix\components\Element',
            ],
            'section'      => [
                'class' => 'common\bitrix\components\Section',
            ],
        ], isset($config['components']) ? $config['components'] : []);

        parent::__construct($config);
    }
}
