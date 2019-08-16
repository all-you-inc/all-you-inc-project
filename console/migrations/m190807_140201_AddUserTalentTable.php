<?php

use yii\db\Migration;

class m190807_140201_AddUserTalentTable extends Migration {

    public function up() {
        $this->createTable('user_talent', [
            'id' => $this->primaryKey(),
            'user_id' => ' int(11) NOT NULL',
            'industry_id' => ' int(11) NOT NULL',
            'talent_id' => ' int(11) NOT NULL',
            'gender' => "enum('female','male','co-ed','all-female','all-male','other') DEFAULT NULL",
            'dj_genre_id' => 'int(11) NULL',
            'instrument_id' => 'int(11) NULL',
            'instrument_spec_id' => 'int(11) NULL',
            'music_genre_id' => 'int(11) NULL',
            'created_at' => ' bigint(20) DEFAULT NULL',
            'created_by' => ' bigint(20) DEFAULT NULL',
            'modified_at' => ' bigint(20) DEFAULT NULL',
            'modified_by' => ' bigint(20) DEFAULT NULL',
            'is_deleted' => 'int(1) DEFAULT "0"',
        ]);
        $this->addForeignKey('user_talent_FK1', 'user_talent', "user_id", 'users', "id");
        $this->addForeignKey('user_talent_FK2', 'user_talent', 'industry_id', 'industry', 'id');
        $this->addForeignKey('user_talent_FK3', 'user_talent', 'talent_id', 'talent_master', 'id');
    }

    public function down() {
        $this->dropTable('user_talent');
    }
}
