<?php

use yii\db\Migration;

class m191031_094437_alterUserTable extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'notification_seen_at', 'bigint(20) default 0');
    }

    public function down()
    {
        $this->dropColumn('users', 'notification_seen_at');
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
