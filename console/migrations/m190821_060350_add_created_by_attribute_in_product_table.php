<?php

use yii\db\Migration;

class m190821_060350_add_created_by_attribute_in_product_table extends Migration
{
    public function up()
    {
        $shop_products = (new \yii\db\Query())
        ->select("*")
        ->from('shop_products')
        ->all();

        if($shop_products != NULL){

            $_admin = (new yii\db\Query())
            ->select('*')
            ->from('auth_assignments')
            ->where(['item_name' => 'admin'])
            ->one();

            if($_admin == NUll){
                throw new yii\base\Exception("Products Found But There Is No Admin First Create Admin And Then Run This Migration");
            }

            $this->addColumn('shop_products', 'created_by', $this->integer()->defaultValue($_admin['user_id']));
            $this->addForeignKey('shop_product_created_by_to_users_id',
                'shop_products', 'created_by', 'users', 'id');
            $this->alterColumn('shop_products', 'created_by', $this->integer());
        } else {

            $this->addColumn('shop_products', 'created_by', $this->integer());
            $this->addForeignKey('shop_product_created_by_to_users_id',
                'shop_products', 'created_by', 'users', 'id');

        }
    }

    public function down()
    {
        echo "m190821_060350_add_created_by_attribute_in_product_table cannot be reverted.\n";

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
