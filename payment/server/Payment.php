<?php

namespace app\payment\server;

use yii\base\InvalidArgumentException;

/**
 * @property string $id
 * @property int $user_id
 * @property float $sum
 */
class Payment
{
    private $id;
    private $userId;
    private $sum;
    
    /**
     * Build payment from params array
     * @param array $params
     * @throws InvalidArgumentException
     */
    public function __construct(array $params)
    {
        if (!isset($params['id']) || !is_string($params['id']) || empty($params['id'])) {
            throw new InvalidArgumentException("id must be not empty string");
        }
        if (
            !isset($params['order_number']) || !is_int($params['order_number']) ||
            $params['order_number'] < 1 || $params['order_number'] > 20
        ) {
            throw new InvalidArgumentException(
                'order_number must be int from 1 to 20 given ' . $params['order_number']
            );
        }
        if (
            !isset($params['commission']) || (!is_float($params['commission']) && !is_int($params['commission'])) ||
            $params['commission'] < 0.5 || $params['commission'] > 2
        ) {
            throw new InvalidArgumentException("commission must be float from 0.5 to 2 given " . $params['commission']);
        }
        if (
            !isset($params['sum']) || (!is_float($params['sum']) && !is_int($params['sum'])) ||
            $params['sum'] < 10 || $params['sum'] > 500
        ) {
            throw new InvalidArgumentException("sum must be float from 10 to 500 given " . $params['sum']);
        }
        $this->id = $params['id'];
        $this->userId = $params['order_number'];
        $this->setSum($params['sum'], $params['commission']);
    }
    
    private function setSum(float $sum, float $commission)
    {
        $this->sum = $sum - ($sum * $commission / 100);
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getSum()
    {
        return round($this->sum, 2);
    }
}
