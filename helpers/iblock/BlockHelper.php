<?php

namespace byiitrix\helpers\iblock;

use byiitrix\helpers\BaseHelper;

/**
 * Class BlockHelper
 * @package byiitrix\helpers\iblock
 */
class BlockHelper extends BaseHelper
{
    /**
     * Try parse name by regular expression and return whatever you want, like that:
     *  - get__city__from__content
     * returns IBlock array with CODE: "city" and IBLOCK_TYPE: "content"
     *
     * - id__city__from__content
     * returns ID of IBlock with CODE: "city" and IBLOCK_TYPE: "content"
     *
     * @param string $name
     *
     * @return array|int|null|string
     */
    public function __get($name)
    {
        if( preg_match('#get__(?P<code>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $code
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            return self::GetByCode($code, $type);
        }

        if( preg_match('#id__(?P<code>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $code
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            return self::GetIDByCode($code, $type);
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

        if( $result = static::checkCache(__METHOD__, func_get_args()) ) {
            return $result;
        } else {
            $result = \CIBlock::GetList($arOrder, $arFilter, $bIncCnt);

            if( $result->SelectedRowsCount() > 0 ) {
                $result = $result->GetNext();
                self::setCache(__METHOD__, func_get_args(), $result);

                return $result;
            }
        }

        return NULL;
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
        if( $result = static::checkCache(__METHOD__, func_get_args()) ) {
            return isset($result['CODE']) ? $result['CODE'] : NULL;
        } else {
            $result = \CIBlock::GetByID($id);

            if( $result->SelectedRowsCount() > 0 ) {
                $result = $result->GetNext();
                self::setCache(__METHOD__, func_get_args(), $result);

                return isset($result['CODE']) ? $result['CODE'] : NULL;
            }
        }

        return NULL;
    }
}
