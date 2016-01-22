<?php
define('YII_DEBUG', true);
define('YII_ENV', 'test');

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php';

Yii::setAlias('tests', __DIR__);


new yii\console\Application([
    'id' => 'test',
    'basePath' => __DIR__
]);
