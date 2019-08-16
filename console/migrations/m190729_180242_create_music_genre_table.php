<?php

use yii\db\Migration;

/**
 * Handles the creation of table `music_genre`.
 */
class m190729_180242_create_music_genre_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('music_genre', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ]
    );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('music_genre');
    }
}
