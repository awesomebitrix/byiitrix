<?php

namespace byiitrix\components;

use yii\db\Connection;

class Element
{
    /**
     * Try parse name by regular expression and return whatever you want, like that:
     *
     * - id__asia__in__city__from__content
     * returns integer id of SECTION "asia" from IBLOCK "city", IBLOCK_TYPE "content"
     *
     * - get__asia__in__city__from__content
     * returns array SECTION "asia" from IBLOCK "city", IBLOCK_TYPE "content"
     *
     * - list_of__city__from__content
     * returns list of section from IBLOCK "city", IBLOCK_TYPE "content"
     *
     * @param string $name
     *
     * @return array|int|null
     * @throws \Exception
     */
    public function __get($name)
    {
        if( preg_match('#^id__(?P<code>[^_].+?[^_])__in__(?P<block>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $code
             * @var string $block
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            $code = str_replace('___', '-', $code);

            return $this->id($code, $block, $type);
        }

        if( preg_match('#^get__(?P<code>[^_].+?[^_])__in__(?P<block>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $code
             * @var string $block
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            $code = str_replace('___', '-', $code);

            return $this->get($code, $block, $type);
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
     * @param string $element
     * @param string $code
     * @param string $type
     *
     * @return int|null
     * @throws \Exception
     */
    public function id($element, $code, $type)
    {
        return \Yii::$app->getDb()->cache(function (Connection $db) use ($element, $code, $type) {
            $sql = <<<SQL
SELECT `b_iblock_element`.`ID`
FROM `b_iblock_element`
INNER JOIN `b_iblock` ON `b_iblock`.`ID` = `b_iblock_element`.`IBLOCK_ID`
WHERE  
    `b_iblock_element`.`CODE` = :ELEMENT_CODE 
    AND `b_iblock`.`CODE` = :BLOCK_CODE 
    AND `b_iblock`.`IBLOCK_TYPE_ID` = :BLOCK_TYPE
LIMIT 1;
SQL;

            return (int)$db->createCommand($sql, [
                ':ELEMENT_CODE' => $element,
                ':BLOCK_CODE'   => $code,
                ':BLOCK_TYPE'   => $type,
            ])->queryScalar() ? : NULL;
        }, 86400);
    }

    /**
     * @param string $element
     * @param string $code
     * @param string $type
     *
     * @return array|null
     * @throws \Exception
     */
    public function get($element, $code, $type)
    {
        $cache = \Yii::$app->getCache();
        $key   = __METHOD__ . ':' . $type . ':' . $code . ':' . $element;
        $value = $cache->get($key);

        if( $value === false ) {
            $id = $this->id($element, $code, $type);

            if( empty($id) ) {
                return NULL;
            }

            $value = \CIBlockElement::GetList([], ['ID' => $id])->GetNext() ? : NULL;

            if( $value !== NULL ) {
                $page = 0;
                do {
                    list($properties, $pages) = $this->pagedSelectProperties($code, $type, $page);

                    if( count($properties) === 0 ) {
                        break;
                    }

                    $ext   = \CIBlockElement::GetList([], ['ID' => $id], false, false, array_merge(['ID', 'IBLOCK_ID'], $properties))->GetNext();
                    $value = array_merge($value, $ext);
                    ++$page;
                } while( $page < $pages );
            }

            $cache->set($key, $value, 30);
        }

        return $value;
    }

    private $_sort = [];

    /**
     * @param array $sort
     *
     * @return static
     */
    public function sort(array $sort = [])
    {
        $this->_sort = $sort;

        return $this;
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
     * @param string $code
     * @param string $type
     *
     * @return array
     * @throws \Exception
     */
    public function listOf($code, $type)
    {
        $blockID = \Yii::$app->bitrix->block->id($code, $type);
        $sort    = $this->_sort;
        $filter  = $this->_filter;

        $this->_sort   = [];
        $this->_filter = [];

        if( empty($blockID) ) {
            return [];
        }

        if( array_key_exists('SORT', $sort) ) {
            $sort['SORT'] = 'ASC';
        }

        $filter['IBLOCK_ID'] = $blockID;

        if( array_key_exists('ACTIVE', $filter) === false ) {
            $filter['ACTIVE'] = 'Y';
        }

        $cache = \Yii::$app->getCache();
        $key   = __METHOD__ . ':' . $type . ':' . $code . ':' . serialize([$sort, $filter]);
        $value = $cache->get($key);

        if( $value === false ) {
            $value  = [];
            $result = \CIBlockElement::GetList($sort, $filter);

            while( $row = $result->GetNext() ) {
                $value[$row['ID']] = $row;
            }

            if( count($value) !== 0 ) {
                $page = 0;
                do {
                    list($properties, $pages) = $this->pagedSelectProperties($code, $type, $page);

                    if( count($properties) === 0 ) {
                        break;
                    }

                    $result = \CIBlockElement::GetList([], ['=ID' => array_keys($value)], false, false, array_merge(['ID', 'IBLOCK_ID'], $properties));

                    while( $row = $result->GetNext() ) {
                        $value[$row['ID']] = array_merge($value[$row['ID']], $row);
                    }

                    ++$page;
                } while( $page < $pages );
            }

            $cache->set($key, $value, 30);
        }

        return $value;
    }

    /**
     * Array of properties if format PROPERTY_#CODE# (for \CIBlockElement::GetList $arSelect) without multiple properties
     * Output paged by 20 properties, because with usage more than ~23-27 mysql failed with error
     *
     * #1116 Too many tables; MariaDB can only use 61 tables in a join
     *
     * @param string $code
     * @param string $type
     * @param int    $page
     *
     * @return array
     */
    public function pagedSelectProperties($code, $type, $page = 0)
    {
        $limit      = 20;
        $properties = \Yii::$app->bitrix->property->filter(['MULTIPLE' => 'N'])->listOf($code, $type);
        $current    = array_slice($properties, $limit * $page, $limit);
        $select     = [];
        $pages      = (int)ceil(count($properties) / $limit);

        foreach( $current as $property ) {
            $select[] = 'PROPERTY_' . $property['CODE'];
        }

        return [
            $select,
            $pages,
        ];
    }
}
