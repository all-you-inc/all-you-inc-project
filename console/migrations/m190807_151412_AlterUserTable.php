<?php

use yii\db\Migration;

class m190807_151412_AlterUserTable extends Migration {

    public function up() {
        $this->dropIndex('idx-users-phone', 'users');
    }

    public function down() {
        echo "m190807_151412_AlterUserTable cannot be reverted.\n";

        return false;
    }

}
