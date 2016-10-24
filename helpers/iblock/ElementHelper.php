<?php

namespace byiitrix\helpers\iblock;

use byiitrix\helpers\BaseHelper;

class ElementHelper extends BaseHelper
{
    /**
     * @param integer $id
     * @param bool    $returnIBElement
     *
     * @return \_CIBElement|array|null
     */
    public static function GetByID($id, $returnIBElement = true)
    {
        $id = (int)$id;

        if( $id <= 0 ) {
            return NULL;
        }

        return self::cache(function () use ($id, $returnIBElement) {
            $result = \CIBlockElement::GetList(['SORT' => 'ASC'], [
                'ID'                => $id,
                'CHECK_PERMISSIONS' => 'N',
            ]);

            $row = $returnIBElement ? $result->GetNextElement() : $result->GetNext();

            return $row ? : NULL;
        });
    }

    /**
     * @param string         $code
     * @param integer|string $block
     * @param string         $type
     * @param bool           $returnIBElement
     *
     * @return \_CIBElement|null
     */
    public static function GetByCode($code, $block = NULL, $type = NULL, $returnIBElement = true)
    {
        return self::cache(function () use ($code, $block, $type, $returnIBElement) {
            $filter = [
                'CODE'              => $code,
                'CHECK_PERMISSIONS' => 'N',
            ];

            if( is_numeric($block) ) {
                $filter['IBLOCK_ID'] = (int)$block;
            } else {
                $filter['IBLOCK_CODE'] = $block;
                $filter['IBLOCK_TYPE'] = $type;
            }

            $result = \CIBlockElement::GetList(['SORT' => 'ASC'], $filter);

            $row = $returnIBElement ? $result->GetNextElement() : $result->GetNext();

            return $row ? : NULL;
        });
    }

    /**
     * @param string         $code
     * @param integer|string $block
     * @param string         $type
     *
     * @return int|null
     */
    public static function GetIDByCode($code, $block = NULL, $type = NULL)
    {
        $element = self::GetByCode($code, $block, $type);

        return isset($element->fields['ID']) ? (int)$element->fields['ID'] : NULL;
    }

    /**
     * @param array $order
     * @param array $filter
     * @param bool  $group
     * @param bool  $nav
     * @param array $select
     *
     * @return array
     */
    public static function GetList(array $order = ['SORT' => 'ASC'], array $filter = [], $group = false, $nav = false, array $select = [])
    {
        $result = \CIBlockElement::GetList($order, $filter, $group, $nav, $select);
        $rows   = [];

        while( $row = $result->GetNext() ) {
            $rows[] = $row;
        }

        return $rows;
    }

    public static function GetListByBlock($block, $type = NULL)
    {
        $filter = [
            'CHECK_PERMISSIONS' => 'N',
        ];

        if( is_numeric($block) ) {
            $filter['IBLOCK_ID'] = (int)$block;
        } else {
            $filter['IBLOCK_CODE'] = $block;
            $filter['IBLOCK_TYPE'] = $type;
        }

        return self::GetList(['SORT' => 'ASC'], $filter);
    }

    /**
     * @param array $order
     * @param array $filter
     * @param bool  $group
     * @param bool  $nav
     * @param array $select
     *
     * @return \_CIBElement[]
     */
    public static function GetListElements(array $order = ['SORT' => 'ASC'], array $filter = [], $group = false, $nav = false, array $select = [])
    {
        $result = \CIBlockElement::GetList($order, $filter, $group, $nav, $select);
        $rows   = [];

        while( $row = $result->GetNextElement() ) {
            $rows[] = $row;
        }

        return $rows;
    }

    public static function GetListElementsByBlock($block, $type = NULL)
    {
        $filter = [
            'CHECK_PERMISSIONS' => 'N',
        ];

        if( is_numeric($block) ) {
            $filter['IBLOCK_ID'] = (int)$block;
        } else {
            $filter['IBLOCK_CODE'] = $block;
            $filter['IBLOCK_TYPE'] = $type;
        }

        return self::GetListElements(['SORT' => 'ASC'], $filter);
    }

    /**
     * @param string $block
     * @param string $type
     *
     * @return \_CIBElement[]
     */
    public static function ActiveList($block, $type = NULL)
    {
        $filter = [
            'ACTIVE'            => 'Y',
            'CHECK_PERMISSIONS' => 'N',
        ];

        if( is_numeric($block) ) {
            $filter['IBLOCK_ID'] = (int)$block;
        } else {
            $filter['IBLOCK_CODE'] = $block;
            $filter['IBLOCK_TYPE'] = $type;
        }

        $rows   = [];
        $result = \CIBlockElement::GetList(['SORT' => 'ASC'], $filter);

        while( $row = $result->GetNextElement() ) {
            $rows[$row->fields['ID']] = $row;
        }

        return $rows;
    }
}
