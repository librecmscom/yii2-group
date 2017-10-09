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
            'id' => $this->primaryKey()->comment('ID'),
            'group_id' => $this->integer()->comment('Group ID'),
            'user_id' => $this->integer()->comment('User ID'),
            'model_id' => $this->integer()->notNull()->comment('Model ID'),
            'model' => $this->string()->notNull()->comment('Model'),
            'subject' => $this->string()->comment('Subject'),
            'status' => $this->smallInteger(1)->defaultValue(0)->comment('Status'),
            'created_at' => $this->integer()->notNull()->defaultValue(0)->comment('Created At'),
            'updated_at' => $this->integer()->notNull()->defaultValue(0)->comment('updated At'),
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
