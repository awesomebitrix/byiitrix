<?php

namespace byiitrix\helpers\iblock;

use byiitrix\helpers\BaseHelper;

class BlockHelper extends BaseHelper
{
    /**
     * Try parse name by regular expression and return whatever you want, like that:
     *  - get_city_from_content
     * returns IBlock array with CODE: "city" and IBLOCK_TYPE: "content"
     *
     * - id_city_from_content
     * returns ID of IBlock with CODE: "city" and IBLOCK_TYPE: "content"
     *
     * @param string $name
     *
     * @return array|int|null|string
     */
    public function __get($name)
    {
        if( preg_match('#get_(?P<code>.+?)_from_(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $code
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            return self::GetByCode($code, $type);
        }

        if( preg_match('#id_(?P<code>.+?)_from_(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $code
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            return self::GetIDByCode($code, $type);
        }

        if( preg_match('#code_(.+)$#', $name, $matches) ) {
            return self::GetCodeByID($matches[1]);
        }

        return NULL;
    }

    /**
     * @param string $code
     * @param string $type
     *
     * @return array|null
     */
    public static function GetByCode($code, $type = NULL)
    {
        $arOrder  = ['SORT' => 'ASC'];
        $arFilter = ['CODE' => $code, 'TYPE' => $type, 'CHECK_PERMISSIONS' => 'N'];
        $bIncCnt  = false;

        $result = \CIBlock::GetList($arOrder, $arFilter, $bIncCnt);

        return $result->GetNext() ? : NULL;
    }

    /**
     * @param string      $code
     * @param string|null $type
     *
     * @return int|null
     */
    public static function GetIDByCode($code, $type = NULL)
    {
        $iblock = self::GetByCode($code, $type);

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
