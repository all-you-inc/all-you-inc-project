<?php

use yii\db\Migration;

class m190808_105427_CreateMembershipTable extends Migration {

    public function up() {
        $this->createTable('membership', [
            'id' => $this->primaryKey(),
            'title' => ' varchar(256) DEFAULT NULL',
            'level' => ' int(2) NULL',
            'price' => ' varchar(64) DEFAULT NULL',
            'status' => 'enum("active","in-active") DEFAULT "in-active"',
            'description' => ' text',
            'category' => 'enum("daily","weekly","monthly","quarterly","semi-annually","annually") DEFAULT "monthly"',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' bigint(20) DEFAULT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' bigint(20) DEFAULT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
        ]);
    }

    public function down() {
        $this->dropTable('membership');
    }
}
