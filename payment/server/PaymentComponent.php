<?php

namespace app\payment\server;

use app\payment\server\db\Payments;
use app\payment\server\db\Users;
use Yii;
use yii\base\Component;
use yii\base\InvalidArgumentException;

/**
 * Payments accept component
 */
class PaymentComponent extends Component
{
    public const EVENT_BEFORE_PAYMENT = 'beforePayment';
    public const EVENT_AFTER_PAYMENT = 'afterPayment';
    public const EVENT_ERROR_PAYMENT = 'errorPayment';
    
    public const EVENT_BEFORE_WALLET_UPD = 'beforeWalletUpdate';
    public const EVENT_AFTER_WALLET_UPD = 'afterWalletUpdate';
    public const EVENT_ERROR_WALLET_UPD = 'errorWalletUpdate';
    /**
     * @var string class for Users repository
     */
    public $wallets = db\Wallets::class;
    
    /**
     * @var string class for Payments repository
     */
    public $payments = db\Payments::class;
    
    /**
     * Return users wallets repo
     * @return db\Wallets
     */
    public function getWallets()
    {
        return new $this->wallets();
    }
    
    /**
     * Update wallet
     * @param int $userId
     * @param float $sum
     */
    public function updateWallet(int $userId, float $sum)
    {
        $event = new WalletEvent([
            'userId' => $userId,
            'sum' => $sum,
        ]);
        $category = self::class . ':' . __METHOD__;
        try {
            $this->trigger(static::EVENT_BEFORE_WALLET_UPD, $event);
            $wallets = $this->getWallets();
            /* @var $wallet Wallet */
            $wallet = $wallets->get($userId);
            if ($wallet === null) {
                $wallet = new Wallet($userId, $sum);
                $wallets->save($wallet);
            } else {
                $wallet->add($sum);
                $wallets->update($wallet);
            }
            Yii::info("Sum {$wallet->getSum()} added to wallet {$wallet->getUserId()}", $category);
            $this->trigger(static::EVENT_AFTER_WALLET_UPD, $event);
        } catch (\Exception $e) {
            $event->error = $e;
            Yii::error("Error while updating wallet $userId {$e->getMessage()}", $category);
            $this->trigger(static::EVENT_ERROR_WALLET_UPD, $event);
        }
    }
    
    /**
     * Return payments repo
     * @return Payments
     */
    public function getPayments()
    {
        return new $this->payments();
    }
    
    /**
     * Add payments data
     * @param array $payments array of payments data
     * @throws InvalidArgumentException
     */
    public function pushPayments($payments)
    {
        if (!is_array($payments)) {
            throw new InvalidArgumentException('Payments must be array');
        }
        Yii::$app->queue->push(new BatchPaymentsJob(['payments' => $payments]));
    }
    
    public function addPayment(Payment $payment)
    {
        $event = new PaymentEvent([
            'payment' => $payment,
        ]);
        $category = self::class . ':' .  __METHOD__;
        try {
            $this->trigger(static::EVENT_BEFORE_PAYMENT, $event);
            $this->getPayments()->save($payment);
            Yii::info("Payment {$payment->getId()} successfully added", $category);
            $this->trigger(static::EVENT_AFTER_PAYMENT, $event);
        } catch (\Exception $e) {
            Yii::error("Error while payment {$e->getMessage()}", $category);
            $event->error = $e;
            $this->trigger(static::EVENT_ERROR_PAYMENT, $event);
        }
    }
}
