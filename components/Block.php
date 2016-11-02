<?php

namespace byiitrix\components;

use yii\db\Connection;

class Block
{
    /**
     * Try parse name by regular expression and return whatever you want, like that:
     *
     * - id__city__from__content
     * returns integer id IBLOCK "city",IBLOCK_TYPE "content"
     *
     *  - get__city__from__content
     * returns array IBLOCK "city", IBLOCK_TYPE "content"
     *
     * @param string $name
     *
     * @return array|int|null
     * @throws \Exception
     */
    public function __get($name)
    {
        if( preg_match('#^id__(?P<code>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $code
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            return $this->id($code, $type);
        }

        if( preg_match('#^get__(?P<code>.+?)__from__(?P<type>.+?)$#', $name, $matches) ) {
            /**
             * @var string $code
             * @var string $type
             */

            extract($matches, EXTR_OVERWRITE);

            return $this->get($code, $type);
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
     * @param string $code
     * @param string $type
     *
     * @return int|null
     * @throws \Exception
     */
    public function id($code, $type)
    {
        return \Yii::$app->getDb()->cache(function (Connection $db) use ($code, $type) {
            $sql = <<<SQL
SELECT `b_iblock`.`ID`
FROM `b_iblock`
WHERE `b_iblock`.`CODE` = :BLOCK_CODE AND `b_iblock`.`IBLOCK_TYPE_ID` = :BLOCK_TYPE
LIMIT 1;
SQL;

            return (int)$db->createCommand($sql, [
                ':BLOCK_CODE' => $code,
                ':BLOCK_TYPE' => $type,
            ])->queryScalar() ? : NULL;
        }, 86400);
    }

    /**
     * @param string $code
     * @param string $type
     *
     * @return array|null
     * @throws \Exception
     */
    public function get($code, $type)
    {
        $cache = \Yii::$app->getCache();
        $key   = __METHOD__ . ':' . $type . ':' . $code;
        $value = $cache->get($key);

        if( $value === false ) {
            $value = \CIBlock::GetByID($this->id($code, $type))->GetNext() ? : NULL;

            $cache->set($key, $value, 30);
        }

        return $value;
    }
}
