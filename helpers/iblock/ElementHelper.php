<?php

namespace byiitrix\helpers\iblock;

use byiitrix\helpers\BaseHelper;

class ElementHelper extends BaseHelper
{
    /**
     * @param $id
     *
     * @return \_CIBElement|null
     */
    public static function GetByID($id)
    {
        $id = (int)$id;

        if( $id <= 0 ) {
            return NULL;
        }

        $arOrder   = [];
        $arFilter  = ['ID' => $id];
        $arGroupBy = false;
        $arNav     = false;
        $arSelect  = [];

        $result = \CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNav, $arSelect);

        return $result->GetNextElement() ? : NULL;
    }

    /**
     * @param $code
     *
     * @return \_CIBElement[]
     */
    public static function ActiveList($code)
    {
        $list = [];
        $id   = BlockHelper::GetIDByCode($code);

        if( $id === NULL ) {
            return $list;
        }

        $arOrder   = ['SORT' => 'ASC'];
        $arFilter  = [
            'ACTIVE'    => 'Y',
            'IBLOCK_ID' => $id,
        ];
        $arGroupBy = false;
        $arNav     = false;
        $arSelect  = [];

        $result = \CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNav, $arSelect);

        while( $row = $result->GetNextElement() ) {
            $list[$row->fields['ID']] = $row;
        }

        return $list;
    }

    /**
     * @param string $code
     *
     * @return array
     */
    public static function ActiveNameList($code)
    {
        $list = self::ActiveList($code);
        $out  = [];

        foreach( $list as $item ) {
            $out[$item->fields['ID']] = $item->fields['NAME'];
        }

        return $out;
    }
}
