<?php

namespace yuncms\group\migrations;

use yii\db\Migration;

class M171009101704Create_group_member_table extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%group_member}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'group_id' => $this->integer()->comment('Group ID'),
            'user_id' => $this->integer()->comment('User ID'),
            'role' => $this->smallInteger(1)->comment('Role'),
            'status' => $this->smallInteger(1)->defaultValue(0)->comment('Status'),
            'created_at' => $this->integer()->notNull()->defaultValue(0)->comment('Created At'),
            'updated_at' => $this->integer()->notNull()->defaultValue(0)->comment('Updated At'),
            'expired_at' => $this->integer()->notNull()->defaultValue(0)->comment('Expired At'),
        ], $tableOptions);

        $this->createIndex('{{%group_member_un}}', '{{%group_member}}', ['group_id', 'user_id'], true);
        $this->addForeignKey('{{%group_member_fk}}', '{{%group_member}}', 'group_id', '{{%group}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('{{%group_member_fk1}}', '{{%group_member}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

    }

    public function safeDown()
    {
        $this->dropTable('{{%group_member}}');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M171009101704Create_group_member_table cannot be reverted.\n";

        return false;
    }
    */
}
