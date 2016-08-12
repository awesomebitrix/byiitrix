<?php

namespace byiitrix\helpers\iblock;

class BlockHelper
{
    /**
     * @param $code
     *
     * @return array|null
     */
    public static function GetByCode($code)
    {
        $arOrder  = ['SORT' => 'ASC'];
        $arFilter = ['CODE' => $code, 'CHECK_PERMISSIONS' => 'N'];
        $bIncCnt  = false;

        $result = \CIBlock::GetList($arOrder, $arFilter, $bIncCnt);

        return $result->GetNext() ? : NULL;
    }

    /**
     * @param string $code
     *
     * @return int|null
     */
    public static function GetIDByCode($code)
    {
        $iblock = self::GetByCode($code);

        return $iblock ? (int)$iblock['ID'] : NULL;
    }

    /**
     * @param $id
     *
     * @return string|null
     */
    public static function GetCodeByID($id)
    {
        $result = \CIBlock::GetByID($id);
        $result = $result->GetNext();

        return isset($result['CODE']) ? $result['CODE'] : NULL;
    }
}
