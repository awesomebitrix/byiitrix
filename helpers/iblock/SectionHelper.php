<?php

namespace byiitrix\helpers\iblock;

class SectionHelper
{
    /**
     * @param $blockID
     * @param $code
     *
     * @return \_CIBElement|null
     */
    public static function GetByIBlockIDAndCode($blockID, $code)
    {
        $arOrder  = ['SORT' => 'ASC'];
        $arFilter = ['IBLOCK_ID' => $blockID, 'CODE' => $code];
        $bIncCnt  = false;
        $arSelect = [];
        $arNav    = false;

        $result = \CIBlockSection::GetList($arOrder, $arFilter, $bIncCnt, $arSelect, $arNav);

        return $result->GetNextElement() ? : NULL;
    }
}
