<?php

namespace byiitrix\helpers\iblock;

use byiitrix\helpers\BaseHelper;

class ElementHelper extends BaseHelper
{
    /**
     * @param integer $id
     *
     * @return \_CIBElement|null
     */
    public static function GetByID($id)
    {
        $id = (int)$id;

        if( $id <= 0 ) {
            return NULL;
        }

        $result = \CIBlockElement::GetList(['SORT' => 'ASC'], [
            'ID'                => $id,
            'CHECK_PERMISSIONS' => 'N',
        ]);

        return $result->GetNextElement() ? : NULL;
    }

    /**
     * @param string         $code
     * @param integer|string $block
     * @param string         $type
     *
     * @return \_CIBElement|null
     */
    public static function GetByCode($code, $block = NULL, $type = NULL)
    {
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

        return $result->GetNextElement() ? : NULL;
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
    public static function GetList($order = ['SORT' => 'ASC'], $filter = [], $group = false, $nav = false, $select = [])
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
    public static function GetListElements($order = ['SORT' => 'ASC'], $filter = [], $group = false, $nav = false, $select = [])
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
