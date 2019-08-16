<?php

use yii\db\Migration;

class m190809_150544_SubscriptionPlanSeed extends Migration {

    public function up() {
        $this->insert('membership', [
            'id' => '1',
            'title' => 'ALL TALENT',
            'level' => '1',
            'price' => '00.99',
            'status' => 'active',
            'description' => '<li><b>60GB</b> Disk Space</li>
<li><b>60</b> Email Accounts</li>
<li><b>60GB</b> Bandwidth</li>
<li><b>15</b> Subdomains</li>
<li><b>20</b> Domains</li>',
            'category' => 'monthly'
        ]);
        $this->insert('membership', [
            'id' => '2',
            'title' => 'PRODUCTION',
            'level' => '2',
            'price' => '05.99',
            'status' => 'active',
            'description' => '<li><b>60GB</b> Disk Space</li>
<li><b>60</b> Email Accounts</li>
<li><b>60GB</b> Bandwidth</li>
<li><b>15</b> Subdomains</li>
<li><b>20</b> Domains</li>',
            'category' => 'monthly'
        ]);
        $this->insert('membership', [
            'id' => '3',
            'title' => 'MEDIA PROMOTER',
            'level' => '3',
            'price' => '03.99',
            'status' => 'active',
            'description' => '<li><b>60GB</b> Disk Space</li>
<li><b>60</b> Email Accounts</li>
<li><b>60GB</b> Bandwidth</li>
<li><b>15</b> Subdomains</li>
<li><b>20</b> Domains</li>',
            'category' => 'monthly'
        ]);
    }

    public function down() {
        echo "m190809_150544_SubscriptionPlanSeed cannot be reverted.\n";

        return false;
    }

}
