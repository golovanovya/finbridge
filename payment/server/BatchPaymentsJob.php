<?php

namespace app\payment\server;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\queue\JobInterface;

/**
 * Job for \yii\queue\Queue
 */
class BatchPaymentsJob extends BaseObject implements JobInterface
{
    /**
     * @var array
     */
    public $payments;
    
    /**
     * Execute job
     * @param type $queue
     * @throws InvalidArgumentException
     */
    public function execute($queue)
    {
        if (!is_array($this->payments)) {
            throw new InvalidArgumentException("Invalid payments parameter");
        }
        foreach ($this->payments as $paymentData) {
            try {
                $payment = new Payment((array) $paymentData);
                Yii::$app->payment->addPayment($payment);
            } catch (InvalidArgumentException $e) {
                Yii::error(
                    'Invalid Payment data provided ' . $e->getMessage(),
                    PaymentComponent::class . ':' . __METHOD__
                );
            }
        }
    }
}
