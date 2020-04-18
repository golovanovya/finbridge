<?php

namespace app\payment\server;

use yii\base\Event;

class WalletEvent extends Event
{
    /**
     * @var PaymentComponent
     * {@inheritdoc}
     */
    public $sender;
    
    /**
     * @var int
     */
    public $userId;
    
    /**
     * @var float
     */
    public $sum;
    
    /**
     * @var \Exception
     */
    public $error;
}
