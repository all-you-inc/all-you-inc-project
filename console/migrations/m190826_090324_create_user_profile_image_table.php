<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_profile_image`.
 */
class m190826_090324_create_user_profile_image_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_profile_image', [
            'id' => $this->primaryKey(),
            'image_extension' => $this->string()->notNull(),
            'image_name' => $this->string()->notNull(),
            'folder_name' => $this->string()->notNull(),
            'show_on' => $this->string()->notNull(),
            'is_show' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('user_profile_image_to_user_id_FK', 'user_profile_image', 'user_id', 'users', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user_profile_image');
    }
}
