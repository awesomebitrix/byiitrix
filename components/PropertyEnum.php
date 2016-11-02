<?php

namespace byiitrix\components;

use yii\db\Connection;

class PropertyEnum
{
    /**
     * Try parse name by regular expression and return whatever you want, like that:
     *
     * - id__Y__of__HAS_AIRPORT__in__city__from__content
     * returns integer id of PROPERTY_ENUM "Y" of PROPERTY_CODE "HAS_AIRPORT", IBLOCK "city", IBLOCK_TYPE "content"
     *
     * - get__Y__of__HAS_AIRPORT__in__city__from__content
     * returns array PROPERTY_ENUM "Y" of PROPERTY_CODE "HAS_AIRPORT", IBLOCK "city", IBLOCK_TYPE "content"
     *
     * - list_of__HAS_AIRPORT__in__city__from__content
     * returns list of property enums from PROPERTY_CODE "HAS_AIRPORT", IBLOCK "city", IBLOCK_TYPE "content"
     *
     * @param string $name
     *
     * @return array|int|null|string
     * @throws \Exception
     */
    public function __get($name)
    {
        if( preg_match('#^id__(?P<xml_id>.+?)__of__(?P<prop>.+?)__in__(?P<block>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $xml_id
             * @var string $prop
             * @var string $block
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            return $this->id($xml_id, $prop, $block, $type);
        }

        if( preg_match('#^get__(?P<xml_id>.+?)__of__(?P<prop>.+?)__in__(?P<block>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $xml_id
             * @var string $prop
             * @var string $block
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            return $this->get($xml_id, $prop, $block, $type);
        }

        if( preg_match('#^list_of__(?P<prop>.+?)__in__(?P<block>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $prop
             * @var string $block
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            return $this->listOf($prop, $block, $type);
        }

        return NULL;
    }

    /**
     * Empty setter, nothing to do
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
    }

    /**
     * @param string $xmlID
     * @param string $property
     * @param string $block
     * @param string $type
     *
     * @return int|null
     * @throws \Exception
     */
    public function id($xmlID, $property, $block, $type)
    {
        return \Yii::$app->getDb()->cache(function (Connection $db) use ($xmlID, $property, $block, $type) {
            $sql = <<<SQL
SELECT `b_iblock_property_enum`.`ID`
FROM `b_iblock_property_enum`
INNER JOIN `b_iblock_property` ON `b_iblock_property`.`ID` = `b_iblock_property_enum`.`PROPERTY_ID`
INNER JOIN `b_iblock` ON `b_iblock`.`ID` = `b_iblock_property`.`IBLOCK_ID`
WHERE `b_iblock_property_enum`.`XML_ID` = :XML_ID AND `b_iblock_property`.`CODE` = :PROPERTY_CODE AND `b_iblock`.`CODE` = :BLOCK_CODE AND `b_iblock`.`IBLOCK_TYPE_ID` = :BLOCK_TYPE
LIMIT 1;
SQL;

            return (int)$db->createCommand($sql, [
                ':XML_ID'        => $xmlID,
                ':PROPERTY_CODE' => $property,
                ':BLOCK_CODE'    => $block,
                ':BLOCK_TYPE'    => $type,
            ])->queryScalar() ? : NULL;
        }, 86400);
    }

    /**
     * @param string $xmlID
     * @param string $property
     * @param string $block
     * @param string $type
     *
     * @return array|null
     * @throws \Exception
     */
    public function get($xmlID, $property, $block, $type)
    {
        $cache = \Yii::$app->getCache();
        $key   = __METHOD__ . ':' . $type . ':' . $block . ':' . $property . ':' . $xmlID;
        $value = $cache->get($key);

        if( $value === false ) {
            $value = \CIBlockPropertyEnum::GetByID($this->id($xmlID, $property, $block, $type))->GetNext() ? : NULL;

            $cache->set($key, $value, 30);
        }

        return $value;
    }

    /**
     * @param string $property
     * @param string $block
     * @param string $type
     *
     * @return array|bool|mixed
     */
    public function listOf($property, $block, $type)
    {
        $propertyID = \Yii::$app->bitrix->property->id($property, $block, $type);

        if( empty($propertyID) ) {
            return [];
        }

        $cache = \Yii::$app->getCache();
        $key   = __METHOD__ . ':' . $type . ':' . $block . ':' . $property;
        $value = $cache->get($key);

        if( $value === false ) {
            $value  = [];
            $result = \CIBlockPropertyEnum::GetList([], [
                'PROPERTY_ID' => $propertyID,
            ]);

            while( $row = $result->GetNext() ) {
                $value[$row['ID']] = $row;
            }

            $cache->set($key, $value, 30);
        }

        return $value;
    }
}
