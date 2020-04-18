<?php

namespace app\payment\server;

/**
 * @property int $userId
 * @property float $sum
 */
class Wallet
{
    private $userId;
    private $sum;
    
    public function __construct(int $userId, float $sum = 0)
    {
        $this->userId = $userId;
        $this->sum = $sum;
    }
    
    public function getUserId()
    {
        return $this->userId;
    }
    
    public function getSum()
    {
        return round($this->sum, 2);
    }
    
    /**
     * Add payment to client account
     * @param float $sum
     */
    public function add(float $sum)
    {
        $this->sum += $sum;
    }
}
