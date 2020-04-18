<?php

namespace app\payment\server\db;

/**
 * Repo for payments
 */
class Payments
{
    public static function getTableName()
    {
        return '{{%payments}}';
    }
    
    public function save(\app\payment\server\Payment $payment)
    {
        \Yii::$app
            ->db
            ->createCommand()
            ->insert(static::getTableName(), [
                'id' => $payment->getId(),
                'sum' => $payment->getSum(),
                'user_id' => $payment->getUserId(),
            ])->execute();
    }
}
