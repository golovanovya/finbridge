<?php

namespace app\payment\server\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payments}}`.
 */
class m200418_112225_create_payments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payments}}', [
            'id' => $this->string()->notNull()->unique(),
            'user_id' => $this->integer()->notNull(),
            'sum' => $this->decimal(15, 2)->notNull(),
        ]);
        $this->addPrimaryKey('pk-payments-id', '{{%payments}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%payments}}');
    }
}
