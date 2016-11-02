<?php

namespace byiitrix\components;

use yii\db\Connection;

class Section
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
     * - root_list_of__city__from__content
     * returns root sections from IBLOCK "city", IBLOCK_TYPE "content"
     *
     * - children_of__asia__in__city__from__content
     * returns children sections of SECTION "asia" from IBLOCK "city", IBLOCK_TYPE "content"
     *
     * @param string $name
     *
     * @return array|int|null
     * @throws \Exception
     */
    public function __get($name)
    {
        if( preg_match('#^id__(?P<section>.+?)__in__(?P<block>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $section
             * @var string $block
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            return $this->id($section, $block, $type);
        }

        if( preg_match('#^get__(?P<section>.+?)__in__(?P<block>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $section
             * @var string $block
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            return $this->get($section, $block, $type);
        }

        if( preg_match('#^list_of__(?P<block>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $block
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            return $this->listOf($block, $type);
        }

        if( preg_match('#^root_list_of__(?P<block>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $block
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            return $this->childrenOf(false, $block, $type);
        }

        if( preg_match('#^children_of__(?P<parent>[^_].+?[^_])__in__(?P<block>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $parent
             * @var string $block
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            $parent = str_replace('___', '-', $parent);
            $id     = $this->id($parent, $block, $type);

            if( empty($id) ) {
                return [];
            }

            return $this->childrenOf($id, $block, $type);
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
     * @param string $section
     * @param string $block
     * @param string $type
     *
     * @return int
     * @throws \Exception
     */
    public function id($section, $block, $type)
    {
        return \Yii::$app->getDb()->cache(function (Connection $db) use ($section, $block, $type) {
            $sql = <<<SQL
SELECT `b_iblock_section`.`ID`
FROM `b_iblock_section`
INNER JOIN `b_iblock` ON `b_iblock`.`ID` = `b_iblock_section`.`IBLOCK_ID`
WHERE `b_iblock_section`.`CODE` = :SECTION_CODE AND `b_iblock`.`CODE` = :BLOCK_CODE AND `b_iblock`.`IBLOCK_TYPE_ID` = :BLOCK_TYPE;
SQL;

            return (int)$db->createCommand($sql, [
                ':SECTION_CODE' => $section,
                ':BLOCK_CODE'   => $block,
                ':BLOCK_TYPE'   => $type,
            ])->queryScalar() ? : NULL;
        }, 86400);
    }

    /**
     * @param string $section
     * @param string $block
     * @param string $type
     *
     * @return array
     * @throws \Exception
     */
    public function get($section, $block, $type)
    {
        $cache = \Yii::$app->getCache();
        $key   = __METHOD__ . ':' . $type . ':' . $block . ':' . $section;
        $value = $cache->get($key);

        if( $value === false ) {
            $id     = $this->id($section, $block, $type);
            $value  = NULL;
            $values = $this->filter(['ID' => $id])->listOf($block, $type);
            $value  = current($values);

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
     * Returns full list of section in infoblock
     *
     * @param string $block
     * @param string $type
     *
     * @return array
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
            $result = \CIBlockSection::GetList([], $filter);

            while( $row = $result->GetNext() ) {
                $value[$row['ID']] = $row;
            }

            $value = $this->updateViaUserFields($value, $blockID);

            $cache->set($key, $value, 30);
        }

        if( is_array($value) === false ) {
            $value = [];
        }

        return $value;
    }

    /**
     * Returns children sections of picked section in infoblock
     *
     * @param string|false $parent
     * @param string       $block
     * @param string       $type
     *
     * @return array
     */
    public function childrenOf($parent, $block, $type)
    {
        $filter = $this->_filter;

        $filter['SECTION_ID'] = $parent;

        return $this->filter($filter)->listOf($block, $type);
    }

    /**
     * @param array $sections
     * @param int   $blockID
     *
     * @return array
     * @throws \Exception
     */
    protected function updateViaUserFields(array $sections, $blockID)
    {
        if( count($sections) === 0 ) {
            return [];
        }

        $fields = \Yii::$app->getDb()->cache(function (Connection $db) use ($blockID) {
            $sql = <<<SQL
SELECT `FIELD_NAME`
FROM `b_user_field`
WHERE `ENTITY_ID` = :ENTITY_ID;
SQL;

            return $db->createCommand($sql, [
                ':ENTITY_ID' => "IBLOCK_{$blockID}_SECTION",
            ])->queryColumn();
        }, 86400);

        $result = \CIBlockSection::GetList([], [
            'IBLOCK_ID' => $blockID,
            '=ID'       => array_keys($sections),
        ], false, array_merge([
            'ID',
            'IBLOCK_ID',
        ], $fields));

        while( $row = $result->GetNext() ) {
            $sections[$row['ID']] = array_merge($sections[$row['ID']], $row);
        }

        return $sections;
    }
}
