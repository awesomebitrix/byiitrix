<?php

namespace byiitrix\helpers\iblock;

class PropertyEnumHelper
{
    /**
     * @param int $propertyID
     *
     * @return array
     */
    public static function GetListByPropertyID($propertyID)
    {
        $list       = [];
        $propertyID = (int)$propertyID;

        if( $propertyID <= 0 ) {
            return $list;
        }

        $arOrder  = ['SORT' => 'ASC'];
        $arFilter = ['PROPERTY_ID' => $propertyID];

        $result = \CIBlockPropertyEnum::GetList($arOrder, $arFilter);

        while( $row = $result->GetNext() ) {
            $list[$row['ID']] = $row;
        }

        return $list;
    }

    /**
     * @param int    $propertyID
     * @param string $xmlID
     *
     * @return int|null
     */
    public static function GetIDByXmlID($propertyID, $xmlID)
    {
        $list = self::GetListByPropertyID($propertyID);

        foreach( $list as $item ) {
            if( $item['XML_ID'] === $xmlID ) {
                return (int)$item['ID'];
            }
        }

        return NULL;
    }
}
