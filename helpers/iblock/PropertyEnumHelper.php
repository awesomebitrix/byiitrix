<?php

namespace byiitrix\helpers\iblock;

use byiitrix\helpers\BaseHelper;

class PropertyEnumHelper extends BaseHelper
{
    /**
     * Try parse name by regular expression and return whatever you want, like that:
     *
     * - get__Y__of__USE_SOMETHING__in__city__from__content
     * returns IBlockPropertyEnum array with XML_ID: "Y", PROPERTY_CODE: "CONTACTS", IBLOCK_ID: "city" and IBLOCK_TYPE: "content"
     *
     * - id__Y__of__USE_SOMETHING__in__city__from__content
     * returns ID of IBlockPropertyEnum with XML_ID: "Y", PROPERTY_CODE: "CONTACTS", IBLOCK_ID: "city" and IBLOCK_TYPE: "content"
     *
     * @param string $name
     *
     * @return array|int|null|string
     */
    public function __get($name)
    {
        if( preg_match('#^get__(?P<xml_id>.+?)__of__(?P<prop>.+?)__in__(?P<block>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $xml_id
             * @var string $prop
             * @var string $block
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            return self::GetByXmlID($xml_id, $prop, $block, $type);
        }

        if( preg_match('#^id__(?P<xml_id>.+?)__of__(?P<prop>.+?)__in__(?P<block>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $xml_id
             * @var string $prop
             * @var string $block
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            return self::GetIDByXmlID($xml_id, $prop, $block, $type);
        }

        return NULL;
    }

    /**
     * @param string         $xmlID
     * @param string|integer $property
     * @param string|integer $block
     * @param string         $type
     *
     * @return array|null
     */
    public static function GetByXmlID($xmlID, $property, $block = NULL, $type = NULL)
    {
        if( empty($property) ) {
            return NULL;
        }

        $blockID = NULL;

        if( is_numeric($property) === false ) {
            if( empty($block) ) {
                return NULL;
            }

            $blockID = BlockHelper::GetIDByCode($block, $type);

            if( $blockID === NULL ) {
                return NULL;
            }
        }

        $result = \CIBlockPropertyEnum::GetList([], [
            'PROPERTY_ID' => $property,
            'IBLOCK_ID'   => $blockID,
            'XML_ID'      => $xmlID,
        ]);

        return $result->GetNext() ? : NULL;
    }

    /**
     * @param string         $xmlID
     * @param string|integer $property
     * @param string|integer $block
     * @param string         $type
     *
     * @return int|null
     */
    public static function GetIDByXmlID($xmlID, $property, $block = NULL, $type = NULL)
    {
        $enum = self::GetByXmlID($xmlID, $property, $block, $type);

        return isset($enum['ID']) ? (int)$enum['ID'] : NULL;
    }

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
}
