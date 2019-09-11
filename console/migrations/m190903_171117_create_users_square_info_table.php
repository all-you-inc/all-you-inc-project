<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users_square_info`.
 */
class m190903_171117_create_users_square_info_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('users_square_info', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'card_id' => $this->string(355)->notNull()
        ]);
        $this->addForeignKey('users_square_info_with_users_FK1', 'users_square_info', 'user_id', 'users', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('users_square_info');
    }
}
