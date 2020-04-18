<?php

namespace app\payment\server;

/**
 * Wallet Behavior.
 */
class WalletBehavior extends \yii\base\Behavior
{
    /**
     * @var PaymentComponent
     * @inheritdoc
     */
    public $owner;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            PaymentComponent::EVENT_AFTER_PAYMENT => 'afterPayment',
        ];
    }
    
    /**
     * @param PaymentEvent $event
     */
    public function afterPayment($event)
    {
        /* @var $event PaymentEvent */
        $payment = $event->payment;
        $this->owner->updateWallet($payment->getUserId(), floatval($payment->getSum()));
    }
}
