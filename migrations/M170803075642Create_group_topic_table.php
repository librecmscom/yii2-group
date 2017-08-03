<?php

namespace yuncms\group\migrations;

use yii\db\Migration;

class M170803075642Create_group_topic_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%group_topic}}', [
            'id' => $this->primaryKey()->comment('主键'),
            'group_id' => $this->integer()->comment('群组ID'),
            'user_id' => $this->integer()->comment('用户ID'),
            'model_id' => $this->integer()->notNull()->comment('资源模型ID'),
            'model' => $this->string()->notNull()->comment('资源模型名称'),
            'subject' => $this->string()->comment('资源名称'),
            'status' => $this->smallInteger(1)->defaultValue(0)->comment('状态'),
            'created_at' => $this->integer()->notNull()->defaultValue(0)->comment('创建时间'),
            'updated_at' => $this->integer()->notNull()->defaultValue(0)->comment('更新时间'),
        ], $tableOptions);
        $this->addForeignKey('{{%group_topic_fk}}', '{{%group_topic}}', 'group_id', '{{%group}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('{{%group_topic_fk1}}', '{{%group_topic}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

    }

    public function safeDown()
    {
        $this->dropTable('{{%group_topic}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M170803075642Create_group_topic_table cannot be reverted.\n";

        return false;
    }
    */
}
