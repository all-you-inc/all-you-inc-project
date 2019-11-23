<?php

use yii\db\Migration;

class m191024_105402_AddFanTable extends Migration
{
    public function up()
    {
        $this->createTable('user_fans', [
            'id' => $this->primaryKey(),
            'user_talent_id' => ' int(11) NOT NULL',
            'fan_id' => ' int(11) NOT NULL',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' int(11) NOT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' int(11) NOT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
        ]);
        $this->addForeignKey('user_fans_FK1', 'user_fans', 'user_talent_id', 'user_talent', 'id');
        $this->addForeignKey('user_fans_FK2', 'user_fans', 'fan_id', 'users', 'id');
        $this->addForeignKey('user_fans_FK3', 'user_fans', 'created_by', 'users', 'id');
        $this->addForeignKey('user_fans_FK4', 'user_fans', 'modified_by', 'users', 'id');

        return true;
    }

    public function down()
    {
        $this->dropTable('user_fans');
        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
