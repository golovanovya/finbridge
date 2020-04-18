<?php

namespace app\payment\client;

use yii\base\InvalidArgumentException;

/**
 * @property string $id
 * @property int $user_id
 * @property float $sum
 */
class Payment implements \JsonSerializable
{
    private $id;
    private $userId;
    private $sum;
    private $commission;
    
    /**
     * Payment constructor
     * @param int $order_number
     * @param float $sum
     * @param float $commission
     * @throws InvalidArgumentException
     */
    public function __construct(int $order_number, float $sum, float $commission)
    {
        if (
            !isset($order_number) || !is_int($order_number) ||
            $order_number < 1 || $order_number > 20
        ) {
            throw new InvalidArgumentException("order_number must be int from 1 to 20 given $order_number");
        }
        if (
            !isset($commission) || (!is_float($commission) && !is_int($commission)) ||
            $commission < 0.5 || $commission > 2
        ) {
            throw new InvalidArgumentException("commission must be float from 0.5 to 2 given $commission");
        }
        if (
            !isset($sum) || (!is_float($sum) && !is_int($sum)) ||
            $sum < 10 || $sum > 500
        ) {
            throw new InvalidArgumentException("sum must be float from 10 to 500 given $sum");
        }
        $this->id = $this->uuid();
        $this->userId = $order_number;
        $this->sum = $sum;
        $this->commission = $commission;
    }
    
    protected function uuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    /**
     * Id getter
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * User id getter
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sum getter
     * @return type
     */
    public function getSum()
    {
        $sum = $this->sum;
        $commission = $this->commission;
        $amount = $sum - ($sum * $commission / 100);
        return round($amount, 2);
    }
    
    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'order_number' => $this->userId,
            'sum' => $this->sum,
            'commission' => $this->commission,
        ];
    }
}
