<?php

namespace byiitrix\helpers\iblock;

use byiitrix\helpers\BaseHelper;

class SectionHelper extends BaseHelper
{
    public static function GetByCode($code, $block, $type = NULL, $sectionID = NULL)
    {
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
    }

    /**
     * @param $blockID
     * @param $code
     *
     * @return \_CIBElement|null
     */
    public static function GetByIBlockIDAndCode($blockID, $code)
    {
        $result = \CIBlockSection::GetList(['SORT' => 'ASC'], [
            'IBLOCK_ID' => $blockID,
            'CODE'      => $code,
        ]);

        return $result->GetNextElement() ? : NULL;
    }
}
