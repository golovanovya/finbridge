<?php

namespace app\payment\server;

use yii\base\Event;

class PaymentEvent extends Event
{
    /**
     * @var PaymentComponent
     * {@inheritdoc}
     */
    public $sender;
    
    /**
     * @var Payment
     */
    public $payment;
    
    /**
     * @var \Exception
     */
    public $error;
}
