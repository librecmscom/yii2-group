<?php

namespace yuncms\group\migrations;

use yii\db\Migration;

class M170803074108Create_group_fans_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%group_fans}}', [
            'id' => $this->primaryKey()->comment('主键'),
            'group_id' => $this->integer()->comment('群组ID'),
            'user_id' => $this->integer()->comment('用户ID'),
            'payment_id' => $this->string(50)->comment('支付ID'),
            'fee' => $this->decimal(10, 2)->defaultValue(0.00)->comment('手续费'),
            'status' => $this->smallInteger(1)->defaultValue(0)->comment('状态'),
            'created_at' => $this->integer()->notNull()->defaultValue(0)->comment('创建时间'),
            'updated_at' => $this->integer()->notNull()->defaultValue(0)->comment('更新时间'),
            'expired_at' => $this->integer()->notNull()->defaultValue(0)->comment('过期时间'),
        ], $tableOptions);

        $this->createIndex('{{%group_fans_un}}', '{{%group_fans}}', ['group_id', 'user_id'], true);
        $this->addForeignKey('{{%group_fans_fk}}', '{{%group_fans}}', 'group_id', '{{%group}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('{{%group_fans_fk1}}', '{{%group_fans}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

    }

    public function safeDown()
    {
        $this->dropTable('{{%group_fans}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M170803074108Create_group_fans_table cannot be reverted.\n";

        return false;
    }
    */
}
