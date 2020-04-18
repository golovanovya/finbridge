<?php

namespace app\payment\server\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_wallet}}`.
 */
class m200418_104355_create_user_wallet_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_wallet}}', [
            'user_id' => $this->integer(),
            'sum' => $this->decimal(15, 2)->notNull()->defaultValue(0),
        ]);
        $this->addPrimaryKey('pk-user_wallet-id', '{{%user_wallet}}', 'user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_wallet}}');
    }
}
