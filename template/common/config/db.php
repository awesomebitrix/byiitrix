<?php
/**
 * Local database configuration
 */

global $DB;

$parts = explode(':', $DB->DBHost);
$host  = $parts[0];
$port  = (isset($parts[1]) && is_numeric($parts[1])) ? (int)$parts[1] : 3306;

return [
   'class'               => 'yii\db\Connection',
   'dsn'                 => "mysql:host={$host};port={$port};dbname={$DB->DBName}",
   'username'            => $DB->DBLogin,
   'password'            => $DB->DBPassword,
   'charset'             => 'utf8',
   'enableSchemaCache'   => true,
   'schemaCacheDuration' => 3600,
   'schemaCache'         => 'cache',
];
