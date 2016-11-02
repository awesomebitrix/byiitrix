<?php
/**
 * Main application parameters
 */

return \yii\helpers\ArrayHelper::merge([], file_exists(__DIR__ . '/params.local.php') ? require __DIR__ . '/params.local.php' : []);
