<?php

namespace console\controllers;

use yii\helpers\FileHelper;
use yii\console\Controller;

class HelperController extends Controller
{
    private function helperNamespace()
    {
        return 'core\helpers';
    }

    private function helperPath()
    {
        $namespace = $this->helperNamespace();
        $path      = $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/' . str_replace('\\', '/', $namespace);

        if( is_dir($path) === false ) {
            FileHelper::createDirectory($path);
        }

        return $path;
    }

    public function actionAll()
    {
        $this->actionBlock();
        $this->actionProperty();
        $this->actionPropertyEnum();
    }

    public function actionBlock()
    {
        $namespace = $this->helperNamespace();
        $path      = $this->helperPath();
        $className = 'BlockHelper';
        $fileName  = "{$path}/{$className}.php";

        $idComments  = '';
        $getComments = '';

        $sql = <<<SQL
SELECT `CODE`, `IBLOCK_TYPE_ID` 
FROM `b_iblock` 
ORDER BY `IBLOCK_TYPE_ID`, `CODE`;
SQL;

        $command = \Yii::$app->getDb()->createCommand($sql);

        foreach( $command->queryAll() as $row ) {
            $code = $row['CODE'];
            $type = $row['IBLOCK_TYPE_ID'];
            $idComments .= "\n * @property int   \$id__{$code}__from__{$type}";
            $getComments .= "\n * @property array \$get__{$code}__from__{$type}";
        }

        $helper = <<<PHP
<?php

namespace {$namespace};

/**
 * Class {$className}
 * @package core\helpers
 *{$idComments}
 *{$getComments}
 */
class {$className} extends \\byiitrix\helpers\\{$className}
{
}
PHP;

        file_put_contents($fileName, $helper);
    }

    public function actionProperty()
    {
        $namespace = $this->helperNamespace();
        $path      = $this->helperPath();
        $className = 'PropertyHelper';
        $fileName  = "{$path}/{$className}.php";

        $idComments  = '';
        $getComments = '';

        $sql = <<<SQL
SELECT 
    `b_iblock_property`.`CODE` AS `PROPERTY_CODE`, 
    `b_iblock`.`CODE` AS `IBLOCK_CODE`, 
    `b_iblock`.`IBLOCK_TYPE_ID`
FROM `b_iblock_property`
INNER JOIN `b_iblock` ON `b_iblock`.`ID` = `b_iblock_property`.`IBLOCK_ID`
ORDER BY `b_iblock`.`IBLOCK_TYPE_ID`, `b_iblock`.`CODE`, `b_iblock_property`.`CODE`;
SQL;

        $command = \Yii::$app->getDb()->createCommand($sql);

        foreach( $command->queryAll() as $row ) {
            $property = $row['PROPERTY_CODE'];
            $block    = $row['IBLOCK_CODE'];
            $type     = $row['IBLOCK_TYPE_ID'];
            $idComments .= "\n * @property int   \$id__{$property}__in__{$block}__from__{$type}";
            $getComments .= "\n * @property array \$get__{$property}__in__{$block}__from__{$type}";
        }

        $helper = <<<PHP
<?php

namespace {$namespace};

/**
 * Class {$className}
 * @package core\helpers
 *{$idComments}
 *{$getComments}
 */
class {$className} extends \\byiitrix\helpers\\{$className}
{
}
PHP;

        file_put_contents($fileName, $helper);
    }

    public function actionPropertyEnum()
    {
        $namespace = $this->helperNamespace();
        $path      = $this->helperPath();
        $className = 'PropertyEnumHelper';
        $fileName  = "{$path}/{$className}.php";

        $idComments  = '';
        $getComments = '';

        $sql = <<<SQL
SELECT 
    `b_iblock_property_enum`.`XML_ID`,
    `b_iblock_property`.`CODE` AS `PROPERTY_CODE`, 
    `b_iblock`.`CODE` AS `IBLOCK_CODE`, 
    `b_iblock`.`IBLOCK_TYPE_ID`
FROM `b_iblock_property_enum`
INNER JOIN `b_iblock_property` ON `b_iblock_property`.`ID` = `b_iblock_property_enum`.`PROPERTY_ID`
INNER JOIN `b_iblock` ON `b_iblock`.`ID` = `b_iblock_property`.`IBLOCK_ID`
ORDER BY `b_iblock`.`IBLOCK_TYPE_ID`, `b_iblock`.`CODE`, `b_iblock_property`.`CODE`, `b_iblock_property_enum`.`XML_ID`;
SQL;

        $command = \Yii::$app->getDb()->createCommand($sql);

        foreach( $command->queryAll() as $row ) {
            $xmlID    = $row['XML_ID'];
            $property = $row['PROPERTY_CODE'];
            $block    = $row['IBLOCK_CODE'];
            $type     = $row['IBLOCK_TYPE_ID'];

            $idComments .= "\n * @property int   \$id__{$xmlID}__of__{$property}__in__{$block}__from__{$type}";
            $getComments .= "\n * @property array \$get__{$xmlID}__of__{$property}__in__{$block}__from__{$type}";
        }

        $helper = <<<PHP
<?php

namespace {$namespace};

/**
 * Class {$className}
 * @package core\helpers
 *{$idComments}
 *{$getComments}
 */
class {$className} extends \\byiitrix\helpers\\{$className}
{
}
PHP;

        file_put_contents($fileName, $helper);
    }
}
