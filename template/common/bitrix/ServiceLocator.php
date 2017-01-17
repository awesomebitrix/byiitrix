<?php

namespace common\bitrix;

use yii\helpers\ArrayHelper;
use Bitrix\Main\Data\StaticHtmlCache;

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
            'block'        => components\Block::class,
            'property'     => components\Property::class,
            'propertyEnum' => components\PropertyEnum::class,
            'element'      => components\Element::class,
            'section'      => components\Section::class,
        ], isset($config['components']) ? $config['components'] : []);

        parent::__construct($config);
    }

    /**
     * Clear all bitrix cache containers
     */
    public function flushCache()
    {
        \BXClearCache(true);
        $GLOBALS['CACHE_MANAGER']->CleanAll();
        $GLOBALS['stackCacheManager']->CleanAll();
        StaticHtmlCache::getInstance()->deleteAll();
    }
}
