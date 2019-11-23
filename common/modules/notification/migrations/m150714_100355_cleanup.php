<?php


use yii\db\Migration;

class m150714_100355_cleanup extends Migration
{

    public function up()
    {
        $this->dropColumn('notification', 'obsolete_target_object_model');
        $this->dropColumn('notification', 'obsolete_target_object_id');
        $this->dropColumn('notification', 'created_by');
        $this->dropColumn('notification', 'updated_by');
        $this->dropColumn('notification', 'updated_at');
        $this->addColumn('notification', 'module', "varchar(100) DEFAULT ''");
    }

    public function down()
    {
        echo "m150714_100355_cleanup cannot be reverted.\n";

        return false;
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
