<?php

namespace byiitrix\helpers\iblock;

class PropertyHelper
{
    /**
     * @param string $iBlockCode
     * @param bool   $keyAsCode
     *
     * @return array
     */
    public static function ActiveList($iBlockCode, $keyAsCode = false)
    {
        $arOrder  = ['SORT' => 'ASC'];
        $arFilter = [
            'IBLOCK_ID' => CIBlockHelper::GetIDByCode($iBlockCode),
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
    }
}
