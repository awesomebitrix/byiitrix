<?php

namespace byiitrix\helpers\iblock;

use byiitrix\helpers\BaseHelper;

class SectionHelper extends BaseHelper
{
    /**
     * @param string      $code
     * @param string|int  $block
     * @param string|null $type
     * @param int|null    $sectionID
     *
     * @return mixed
     */
    public static function GetByCode($code, $block, $type = NULL, $sectionID = NULL)
    {
        return self::cache(function () use ($code, $block, $type, $sectionID) {
            $filter = [
                'CODE'              => $code,
                'CHECK_PERMISSIONS' => 'N',
            ];

            if( $sectionID !== NULL ) {
                $filter['SECTION_ID'] = $sectionID;
            }

            if( is_numeric($block) ) {
                $filter['IBLOCK_ID'] = (int)$block;
            } else {
                $filter['IBLOCK_CODE'] = $block;
                $filter['IBLOCK_TYPE'] = $type;
            }

            $result = \CIBlockSection::GetList(['SORT' => 'ASC'], $filter);

            return $result->GetNextElement() ? : NULL;
        });
    }

    /**
     * @param int    $blockID
     * @param string $code
     *
     * @return \_CIBElement|null
     */
    public static function GetByIBlockIDAndCode($blockID, $code)
    {
        return self::cache(function () use ($blockID, $code) {
            $result = \CIBlockSection::GetList(['SORT' => 'ASC'], [
                'IBLOCK_ID' => $blockID,
                'CODE'      => $code,
            ]);

            return $result->GetNextElement() ? : NULL;
        });
    }
}
