<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Payment client controller
 */
class ClientController extends Controller
{
    /**
     * Command for generating test JWT
     * @param string $message token payload
     * @return int
     */
    public function actionRun()
    {
        $paymentsCount = rand(1, 20);
        /* @var $client \app\payment\client\ClientComponent */
        $client = \Yii::$app->client;
        $payments = \app\payment\client\PaymentGenerator::generateArray($paymentsCount);
        $result = $client->sendPayments($payments);
        if ($result) {
            echo 'success' . PHP_EOL;
            return ExitCode::OK;
        } else {
            echo 'error' . PHP_EOL;
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}
