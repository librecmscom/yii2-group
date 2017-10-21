<?php

namespace yuncms\group\migrations;

use yii\db\Migration;

class M161109073613Create_group_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%group}}', [
            'id' => $this->primaryKey()->unsigned()->comment('ID'),
            'user_id' => $this->integer()->unsigned()->notNull()->comment('User ID'),
            'name' => $this->string(50)->notNull()->comment('Name'),
            'logo' => $this->string()->comment('Logo'),
            'price' => $this->decimal(7, 2)->comment('Price'),
            'billing_cycle' => $this->smallInteger(5)->unsigned()->defaultValue(365)->comment('Billing Cycle'),
            'days_free' => $this->smallInteger(5)->unsigned()->defaultValue(30)->comment('Days Free'),
            'introduce' => $this->string(255)->defaultValue('')->comment('Introduce'),
            'allow_publish' => $this->boolean()->defaultValue(true)->comment('Allow Publish'),
            'applicants' => $this->integer()->defaultValue(0)->unsigned()->comment('Applicants'),
            'status' => $this->smallInteger(1)->unsigned()->defaultValue(0)->comment('Status'),
            'blocked_at' => $this->integer()->unsigned()->comment('Blocked At'),
            'created_at' => $this->integer()->unsigned()->notNull()->comment('Created At'),
            'updated_at' => $this->integer()->unsigned()->notNull()->comment('Updated At'),
        ], $tableOptions);

        $this->addForeignKey('{{%group_fk}}', '{{%group}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

    }

    public function safeDown()
    {
        $this->dropTable('{{%group}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M170803073613Create_group_table cannot be reverted.\n";

        return false;
    }
    */
}
