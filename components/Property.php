<?php

namespace byiitrix\components;

use yii\db\Connection;

class Property
{
    /**
     * Try parse name by regular expression and return whatever you want, like that:
     *
     * - id__ZIP__in__city__from__content
     * returns integer id of PROPERTY "ZIP" from IBLOCK "city", IBLOCK_TYPE "content"
     *
     * - get__ZIP__in__city__from__content
     * returns array PROPERTY "ZIP" from IBLOCK "city", IBLOCK_TYPE "content"
     *
     * - list_of__city__from__content
     * returns list of properties from IBLOCK "city", IBLOCK_TYPE "content"
     *
     * @param string $name
     *
     * @return array|int|null
     * @throws \Exception
     */
    public function __get($name)
    {
        if( preg_match('#^id__(?P<prop>.+?)__in__(?P<block>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $prop
             * @var string $block
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            return $this->id($prop, $block, $type);
        }

        if( preg_match('#^get__(?P<prop>.+?)__in__(?P<block>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $prop
             * @var string $block
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            return $this->get($prop, $block, $type);
        }

        if( preg_match('#^list_of__(?P<block>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $block
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            return $this->listOf($block, $type);
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
     * @param string $property
     * @param string $block
     * @param string $type
     *
     * @return int|null
     * @throws \Exception
     */
    public function id($property, $block, $type)
    {
        return \Yii::$app->getDb()->cache(function (Connection $db) use ($property, $block, $type) {
            $sql = <<<SQL
SELECT `b_iblock_property`.`ID`
FROM `b_iblock_property`
INNER JOIN `b_iblock` ON `b_iblock`.`ID` = `b_iblock_property`.`IBLOCK_ID`
WHERE `b_iblock_property`.`CODE` = :PROPERTY_CODE AND `b_iblock`.`CODE` = :BLOCK_CODE AND `b_iblock`.`IBLOCK_TYPE_ID` = :BLOCK_TYPE
LIMIT 1;
SQL;

            return (int)$db->createCommand($sql, [
                ':PROPERTY_CODE' => $property,
                ':BLOCK_CODE'    => $block,
                ':BLOCK_TYPE'    => $type,
            ])->queryScalar() ? : NULL;
        }, 86400);
    }

    /**
     * @param string $property
     * @param string $block
     * @param string $type
     *
     * @return array|null
     * @throws \Exception
     */
    public function get($property, $block, $type)
    {
        $cache = \Yii::$app->getCache();
        $key   = __METHOD__ . ':' . $type . ':' . $block . ':' . $property;
        $value = $cache->get($key);

        if( $value === false ) {
            $value = \CIBlockProperty::GetByID($this->id($property, $block, $type))->GetNext() ? : NULL;

            $cache->set($key, $value, 30);
        }

        return $value;
    }

    private $_filter = [];

    /**
     * @param array $filter
     *
     * @return static
     */
    public function filter(array $filter = [])
    {
        $this->_filter = $filter;

        return $this;
    }

    /**
     * @param string $block
     * @param string $type
     *
     * @return array
     * @throws \Exception
     */
    public function listOf($block, $type)
    {
        $blockID = \Yii::$app->bitrix->block->id($block, $type);
        $filter  = $this->_filter;

        $this->_filter = [];

        if( empty($blockID) ) {
            return [];
        }

        $filter['IBLOCK_ID'] = $blockID;

        $cache = \Yii::$app->getCache();
        $key   = __METHOD__ . ':' . $type . ':' . $block . ':' . serialize($filter);
        $value = $cache->get($key);

        if( $value === false ) {
            $value  = [];
            $result = \CIBlockProperty::GetList([], $filter);

            while( $row = $result->GetNext() ) {
                $value[$row['ID']] = $row;
            }

            $cache->set($key, $value, 30);
        }

        return $value;
    }
}
