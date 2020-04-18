<?php

namespace app\payment\client;

/**
 * Payment builder
 */
abstract class PaymentGenerator
{
    /**
     * Build random payment
     * @return \app\payment\client\Payment
     */
    public static function generateRandom()
    {
        $orderNumber = rand(1, 20);
        $sum = round(rand(1000, 50000) / 100, 2);
        $commission = round(rand(5, 20) / 10, 2);
        return new Payment($orderNumber, $sum, $commission);
    }
    
    /**
     * Generate array of rand payments
     * @param int $count
     * @return Payment[]
     */
    public static function generateArray(int $count)
    {
        $payments = [];
        for ($i = 0; $i < $count; $i++) {
            $payment = static::generateRandom();
            $payments[] = $payment;
            \Yii::info(\yii\helpers\Json::encode($payment), ClientComponent::class . ':created');
        }
        return $payments;
    }
}
