<?php

namespace app\payment\server\db;

/**
 * Repo for users wallets
 */
class Wallets
{
    public static function getTableName()
    {
        return '{{%user_wallet}}';
    }
    
    /**
     * Get user
     * @param int $id
     * @return \app\payments\server\Wallet
     */
    public function get(int $id)
    {
        $row = (new \yii\db\Query())
            ->select('*')
            ->from(static::getTableName())
            ->where(['user_id' => $id])
            ->limit(1)
            ->one();
        if ($row) {
            return new \app\payment\server\Wallet($row['user_id'], $row['sum']);
        }
    }
    
    /**
     * Update
     * @param \app\payment\server\Wallet $userWallet
     */
    public function update(\app\payment\server\Wallet $userWallet)
    {
        \Yii::$app
            ->db
            ->createCommand()
            ->update(
                static::getTableName(),
                ['sum' => $userWallet->getSum()],
                ['user_id' => $userWallet->getUserId()]
            )->execute();
    }
    
    /**
     * Insert
     * @param \app\payment\server\Wallet $userWallet
     */
    public function save(\app\payment\server\Wallet $userWallet)
    {
        \Yii::$app
            ->db
            ->createCommand()
            ->insert(
                static::getTableName(),
                [
                    'user_id' => $userWallet->getUserId(),
                    'sum' => $userWallet->getSum(),
                ]
            )->execute();
    }
}
