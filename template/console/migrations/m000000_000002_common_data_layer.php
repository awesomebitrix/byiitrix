<?php

use core\Codex;
use yii\db\Migration;

class m000000_000002_common_data_layer extends Migration
{
    public function safeUp()
    {
        $type   = new \CIBlockType();
        $typeID = $type->Add([
            'ID'       => Codex::TYPE_SYSTEM,
            'SECTIONS' => 'Y',
            'SORT'     => 100,
            'LANG'     => [
                'ru' => [
                    'NAME' => 'Система',
                ],
                'en' => [
                    'NAME' => 'System',
                ],
            ],
        ]);

        if( empty($typeID) ) {
            $error = trim(filter_var($type->LAST_ERROR, FILTER_SANITIZE_STRING));
            \yii\helpers\Console::printError($error);

            return false;
        }

        //

        $type   = new \CIBlockType();
        $typeID = $type->Add([
            'ID'       => Codex::TYPE_CATALOG,
            'SECTIONS' => 'Y',
            'SORT'     => 200,
            'LANG'     => [
                'ru' => [
                    'NAME' => 'Каталог',
                ],
                'en' => [
                    'NAME' => 'Catalog',
                ],
            ],
        ]);

        if( empty($typeID) ) {
            $error = trim(filter_var($type->LAST_ERROR, FILTER_SANITIZE_STRING));
            \yii\helpers\Console::printError($error);

            return false;
        }

        $block   = new \CIBlock();
        $blockID = $block->Add([
            'ACTIVE'           => 'Y',
            'CODE'             => Codex::BLOCK_PRODUCT,
            'SITE_ID'          => \core\Codex::SITE_ID_MAIN,
            'IBLOCK_TYPE_ID'   => $typeID,
            'NAME'             => 'Продукция',
            'PICTURE'          => NULL,
            'DESCRIPTION'      => '',
            'DESCRIPTION_TYPE' => NULL,
            'SORT'             => 100,
            'LIST_PAGE_URL'    => '#SITE_DIR#/catalog/',
            'SECTION_PAGE_URL' => '#SITE_DIR#/catalog/#SECTION_CODE_PATH#/',
            'DETAIL_PAGE_URL'  => '#SITE_DIR#/catalog/#SECTION_CODE_PATH#/#ELEMENT_CODE#/',
            'INDEX_ELEMENT'    => 'Y',
            'INDEX_SECTION'    => 'Y',
            'SECTIONS_NAME'    => 'Разделы',
            'SECTION_NAME'     => 'Раздел',
            'ELEMENTS_NAME'    => 'Элементы',
            'ELEMENT_NAME'     => 'Элемент',
            'GROUP_ID'         => [
                \core\Codex::GROUP_ID_ADMIN => \core\Codex::PERMISSION_FULL,
                \core\Codex::GROUP_ID_ALL   => \core\Codex::PERMISSION_READ,
            ],
            'FIELDS'           => [
                'CODE' => [
                    'IS_REQUIRED'   => 'Y',
                    'DEFAULT_VALUE' => [
                        'UNIQUE'          => 'Y',
                        'TRANSLITERATION' => 'Y',
                        'TRANS_LEN'       => 100,
                        'TRANS_CASE'      => 'L',
                        'TRANS_SPACE'     => '-',
                        'TRANS_OTHER'     => '-',
                        'TRANS_EAT'       => 'Y',
                    ],
                ],
            ],
        ]);

        if( empty($blockID) ) {
            $error = trim(filter_var($block->LAST_ERROR, FILTER_SANITIZE_STRING));
            \yii\helpers\Console::printError($error);

            return false;
        }

        //

        $type   = new \CIBlockType();
        $typeID = $type->Add([
            'ID'       => Codex::TYPE_CONTENT,
            'SECTIONS' => 'Y',
            'SORT'     => 300,
            'LANG'     => [
                'ru' => [
                    'NAME' => 'Контент',
                ],
                'en' => [
                    'NAME' => 'Content',
                ],
            ],
        ]);

        if( empty($typeID) ) {
            $error = trim(filter_var($type->LAST_ERROR, FILTER_SANITIZE_STRING));
            \yii\helpers\Console::printError($error);

            return false;
        }

        return true;
    }

    public function safeDown()
    {
        \CIBlockType::Delete(Codex::TYPE_SYSTEM);
        \CIBlockType::Delete(Codex::TYPE_CATALOG);
        \CIBlockType::Delete(Codex::TYPE_CONTENT);

        return true;
    }
}
