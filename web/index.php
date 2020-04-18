<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// comment out the following two lines when deployed to production
if (getenv('YII_DEBUG')) {
    define('YII_DEBUG', getenv('YII_DEBUG') === 'true');
}
if (getenv('YII_ENV')) {
    define('YII_ENV', getenv('YII_ENV'));
}

require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
