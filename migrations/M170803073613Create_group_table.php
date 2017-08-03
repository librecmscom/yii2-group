<?php

namespace yuncms\group\migrations;

use yii\db\Migration;

class M170803073613Create_group_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%group}}', [
            'id' => $this->primaryKey()->comment('主键'),
            'name' => $this->string(50)->notNull()->comment('群组名字'),
            'logo' => $this->string()->notNull()->comment('群组logo'),
            'user_id' => $this->integer()->notNull()->comment('创建者ID'),
            'price' => $this->decimal(7, 2)->comment('加入价格'),
            'introduce' => $this->string(255)->defaultValue('')->comment('群组介绍'),
            'allow_publish' => $this->boolean()->defaultValue(true)->comment('是否允许发布内容'),
            'applicants' => $this->integer()->defaultValue(0)->unsigned()->comment('加入人数'),
            'status' => $this->smallInteger(1)->defaultValue(0)->comment('状态'),
            'blocked_at' => $this->integer()->comment('群组锁定时间'),
            'created_at' => $this->integer()->notNull()->comment('创建时间'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
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
