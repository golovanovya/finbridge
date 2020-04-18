<?php

$db = require __DIR__ . '/db.php';

return [
    'log' => [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning'],
                'except' => [app\payment\server\PaymentComponent::class . ':*'],
            ],
            [
                'class' => yii\log\FileTarget::class,
                'logVars' => [],
                'logFile' => '@runtime/logs/payment.log',
                'categories' => [app\payment\server\PaymentComponent::class . ':*'],
            ],
        ],
    ],
    'db' => $db,
    'jwt' => [
        'class' => \sizeg\jwt\Jwt::class,
        'key' => getenv('JWT_KEY'),
    ],
    'queue' => [
        'class' => \yii\queue\db\Queue::class,
        'as log' => \yii\queue\LogBehavior::class,
        'mutex' => \yii\mutex\MysqlMutex::class,
    ],
    'payment' => [
        'class' => \app\payment\server\PaymentComponent::class,
        'as paymentListener' => app\payment\server\WalletBehavior::class
    ],
];
