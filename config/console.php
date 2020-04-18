<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$commonComponents = require __DIR__ . '/common_components.php';
if (isset($commonComponents['log'])) {
    $commonComponents['log']['targets'][] = [
        'class' => \app\payment\client\FileTarget::class,
        'logVars' => [],
        'levels' => ['info'],
        'logFile' => '@runtime/logs/client.log',
        'categories' => [\app\payment\client\ClientComponent::class . ':created'],
    ];
}
$components = [
    'cache' => [
        'class' => 'yii\caching\FileCache',
    ],
    'db' => $db,
    'client' => [
        'class' => \app\payment\client\ClientComponent::class,
        'url' => getenv('PAYMENT_URL'),
        'alg' => getenv('JWT_ALG'),
    ],
];

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'queue'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => array_merge($commonComponents, $components),
    'params' => $params,
    'controllerMap' => [
        'migrate' => [
            'class' => yii\console\controllers\MigrateController::class,
            'migrationPath' => null,
            'migrationNamespaces' => [
                'app\payment\server\migrations',
                'yii\queue\db\migrations',
            ]
        ],
        /* 'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ], */
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
