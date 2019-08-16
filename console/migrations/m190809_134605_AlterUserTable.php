<?php

use yii\db\Migration;

class m190809_134605_AlterUserTable extends Migration {

    public function up() {
        $this->addColumn('users', 'city', $this->string()->Null());
        $this->addColumn('users', 'state', $this->string()->Null());
        $this->addColumn('users', 'country', $this->string()->Null());
    }

    public function down() {
        echo "m190809_134605_AlterUserTable cannot be reverted.\n";

        return false;
    }
}
