<?php
/**
 * Main application parameters
 */

$local = str_replace('.php', '.local.php', __FILE__);

return \yii\helpers\ArrayHelper::merge([], file_exists($local) ? require $local : []);
