<?php

namespace yuncms\group\migrations;

use yii\db\Migration;

class M171009101705Create_group_order_table extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%group_order}}', [
            'id' => $this->primaryKey()->unsigned()->comment('ID'),
            'group_id' => $this->integer()->unsigned()->comment('Group ID'),
            'user_id' => $this->integer()->unsigned()->comment('User ID'),
            'payment_id' => $this->string(50)->comment('Payment ID'),
            'fee' => $this->decimal(10, 2)->defaultValue(0.00)->comment('Fee'),
            'status' => $this->smallInteger(1)->unsigned()->defaultValue(0)->comment('Status'),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Created At'),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Updated At'),
            'expired_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Expired At'),
        ], $tableOptions);

        $this->createIndex('{{%group_order_un}}', '{{%group_order}}', ['group_id', 'user_id'], true);
        $this->addForeignKey('{{%group_order_fk}}', '{{%group_order}}', 'group_id', '{{%group}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('{{%group_order_fk1}}', '{{%group_order}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%group_order}}');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M171009101705Create_group_order_table cannot be reverted.\n";

        return false;
    }
    */
}
