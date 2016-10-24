<?php

namespace byiitrix\helpers\iblock;

use byiitrix\helpers\BaseHelper;

class PropertyHelper extends BaseHelper
{
    /**
     * Try parse name by regular expression and return whatever you want, like that:
     *
     * - get__CONTACTS__in__city__from__content
     * returns IBlockProperty array with CODE: "CONTACTS", IBLOCK_ID: "city" and IBLOCK_TYPE: "content"
     *
     * - id__CONTACTS__in__city__from__content
     * returns ID of IBlockProperty with CODE: "CONTACTS", IBLOCK_ID: "city" and IBLOCK_TYPE: "content"
     *
     * @param string $name
     *
     * @return array|int|null|string
     */
    public function __get($name)
    {
        if( preg_match('#^get__(?P<prop>.+?)__in__(?P<block>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $prop
             * @var string $block
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            return self::GetByCode($prop, $block, $type);
        }

        if( preg_match('#^id__(?P<prop>.+?)__in__(?P<block>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $prop
             * @var string $block
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            return self::GetIDByCode($prop, $block, $type);
        }

        return NULL;
    }

    /**
     * @param string         $code  Property code
     * @param string|integer $block IBlock CODE or ID
     * @param string         $type  IBlock type, in case of using IBlock ID
     *
     * @return array|null
     */
    public static function GetByCode($code, $block, $type = NULL)
    {
        return self::cache(function () use ($code, $block, $type) {
            $blockID = (int)$block;

            if( is_numeric($block) === false ) {
                $blockID = BlockHelper::GetIDByCode($block, $type);
            }

            if( empty($blockID) ) {
                return NULL;
            }

            $result = \CIBlockProperty::GetList([], [
                'IBLOCK_ID'         => $blockID,
                'CODE'              => $code,
                'CHECK_PERMISSIONS' => 'N',
            ]);

            return $result->GetNext() ? : NULL;
        });
    }

    /**
     * @param string         $code  Property code
     * @param string|integer $block IBlock CODE or ID
     * @param string         $type  IBlock type, in case of using IBlock ID
     *
     * @return integer|null
     */
    public static function GetIDByCode($code, $block, $type = NULL)
    {
        return self::cache(function () use ($code, $block, $type) {
            $property = self::GetByCode($code, $block, $type);

            return isset($property['ID']) ? (int)$property['ID'] : NULL;
        });
    }

    /**
     * @param string $blockCode
     * @param bool   $keyAsCode
     *
     * @return array
     */
    public static function ActiveList($blockCode, $keyAsCode = false)
    {
        return self::cache(function () use ($blockCode, $keyAsCode) {
            $arOrder  = ['SORT' => 'ASC'];
            $arFilter = [
                'IBLOCK_ID' => BlockHelper::GetIDByCode($blockCode),
            ];

            $result = \CIBlockProperty::GetList($arOrder, $arFilter);
            $out    = [];

            while( $row = $result->GetNext() ) {
                $key = $row['ID'];

                if( $keyAsCode === true && !empty($row['CODE']) ) {
                    $key = $row['CODE'];
                }

                $out[$key] = $row;
            }

            return $out;
        });
    }
}
