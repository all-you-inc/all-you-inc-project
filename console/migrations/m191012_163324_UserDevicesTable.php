<?php

use yii\db\Migration;

class m191012_163324_UserDevicesTable extends Migration {

    public function up() {
        $this->createTable('user_devices', [
            'id' => $this->primaryKey(),
            'user_id' => ' bigint(20) DEFAULT NULL',
            'token' => ' varchar(512) NOT NULL',
            'type' => ' varchar(20) NOT NULL',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
        ]);
        return true;
        
    }

    public function down() {
        $this->dropTable('user_devices');
        return true;
    }

    
}
