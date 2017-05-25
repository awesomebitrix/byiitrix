<?php

namespace console\controllers;

use yii\helpers\FileHelper;
use yii\console\Controller;

class HelperController extends Controller
{
    private function helperNamespace()
    {
        return 'common\bitrix\components';
    }

    private function helperPattern()
    {
        return '#( \* \@internal\s\{begin\}).*( \* \@internal \{end\})#Us';
    }

    private function helperPath()
    {
        $namespace = $this->helperNamespace();
        $path      = APP_ROOT . '/' . str_replace('\\', '/', $namespace);

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
        $className = 'Block';
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

            if( preg_match('#[/\#\-\!\?\=]+#', $code . $type) ) {
                continue;
            }

            $idComments .= "\n * @property int   \$id__{$code}__from__{$type}";
            $getComments .= "\n * @property array \$get__{$code}__from__{$type}";
        }

        $internalBody = <<<BODY
 *{$idComments}
 *{$getComments}
BODY;

        $contents = <<<PHP
<?php

namespace {$namespace};

/**
 * Class {$className}
 * @package {$namespace}
 *
 * @internal {begin}
{$internalBody}
 * @internal {end}
 */
class {$className} extends \\byiitrix\components\\{$className}
{
}

PHP;

        if( file_exists($fileName) ) {
            $pattern          = $this->helperPattern();
            $originalContents = file_get_contents($fileName);

            if( preg_match($pattern, $originalContents) ) {
                $contents = preg_replace($pattern, '$1' . PHP_EOL . $internalBody . PHP_EOL . '$2', $originalContents);
            }
        }

        file_put_contents($fileName, $contents);
    }

    public function actionProperty()
    {
        $namespace = $this->helperNamespace();
        $path      = $this->helperPath();
        $className = 'Property';
        $fileName  = "{$path}/{$className}.php";

        $idComments  = '';
        $getComments = '';

        $sql = <<<SQL
SELECT 
    `b_iblock_property`.`CODE` AS `PROPERTY_CODE`, 
    `b_iblock_property`.`PROPERTY_TYPE` AS `PROPERTY_TYPE`, 
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

            if( preg_match('#[/\#\-\!\?\=]+#', $property . $block . $type) ) {
                continue;
            }

            $idComments .= "\n * @property int   \$id__{$property}__in__{$block}__from__{$type}";
            $getComments .= "\n * @property array \$get__{$property}__in__{$block}__from__{$type}";
        }

        $internalBody = <<<BODY
 *{$idComments}
 *{$getComments}
BODY;

        $contents = <<<PHP
<?php

namespace {$namespace};

/**
 * Class {$className}
 * @package {$namespace}
 *
 * @internal {begin}
{$internalBody}
 * @internal {end}
 */
class {$className} extends \\byiitrix\components\\{$className}
{
}

PHP;

        if( file_exists($fileName) ) {
            $pattern          = $this->helperPattern();
            $originalContents = file_get_contents($fileName);

            if( preg_match($pattern, $originalContents) ) {
                $contents = preg_replace($pattern, '$1' . PHP_EOL . $internalBody . PHP_EOL . '$2', $originalContents);
            }
        }

        file_put_contents($fileName, $contents);
    }

    public function actionPropertyEnum()
    {
        $namespace = $this->helperNamespace();
        $path      = $this->helperPath();
        $className = 'PropertyEnum';
        $fileName  = "{$path}/{$className}.php";

        $idComments     = '';
        $getComments    = '';
        $listOfComments = '';

        $sql = <<<SQL
SELECT 
    `b_iblock_property_enum`.`XML_ID`,
    `b_iblock_property`.`CODE` AS `PROPERTY_CODE`, 
    `b_iblock_property`.`PROPERTY_TYPE` AS `PROPERTY_TYPE`, 
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

            if( preg_match('#[/\#\-\!\?\=]+#', $xmlID . $property . $block . $type) ) {
                continue;
            }

            $idComments .= "\n * @property int   \$id__{$xmlID}__of__{$property}__in__{$block}__from__{$type}";
            $getComments .= "\n * @property array \$get__{$xmlID}__of__{$property}__in__{$block}__from__{$type}";
        }

        $sql = <<<SQL
SELECT 
    `b_iblock_property`.`CODE` AS `PROPERTY_CODE`, 
    `b_iblock_property`.`PROPERTY_TYPE` AS `PROPERTY_TYPE`, 
    `b_iblock`.`CODE` AS `IBLOCK_CODE`, 
    `b_iblock`.`IBLOCK_TYPE_ID`
FROM `b_iblock_property`
INNER JOIN `b_iblock` ON `b_iblock`.`ID` = `b_iblock_property`.`IBLOCK_ID`
WHERE `b_iblock_property`.`PROPERTY_TYPE` = 'L'
ORDER BY `b_iblock`.`IBLOCK_TYPE_ID`, `b_iblock`.`CODE`, `b_iblock_property`.`CODE`;
SQL;

        $command = \Yii::$app->getDb()->createCommand($sql);

        foreach( $command->queryAll() as $row ) {
            $property = $row['PROPERTY_CODE'];
            $block    = $row['IBLOCK_CODE'];
            $type     = $row['IBLOCK_TYPE_ID'];

            if( preg_match('#[/\#\-\!\?\=]+#', $property . $block . $type) ) {
                continue;
            }

            $listOfComments .= "\n * @property array \$list_of__{$property}__in__{$block}__from__{$type}";
        }

        $internalBody = <<<BODY
 *{$listOfComments}
 *{$idComments}
 *{$getComments}
BODY;

        $contents = <<<PHP
<?php

namespace {$namespace};

/**
 * Class {$className}
 * @package {$namespace}
 *
 * @internal {begin}
{$internalBody}
 * @internal {end}
 */
class {$className} extends \\byiitrix\components\\{$className}
{
}

PHP;

        if( file_exists($fileName) ) {
            $pattern          = $this->helperPattern();
            $originalContents = file_get_contents($fileName);

            if( preg_match($pattern, $originalContents) ) {
                $contents = preg_replace($pattern, '$1' . PHP_EOL . $internalBody . PHP_EOL . '$2', $originalContents);
            }
        }

        file_put_contents($fileName, $contents);
    }
}
