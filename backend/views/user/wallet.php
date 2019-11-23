<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\auth\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use common\models\usertalent\UserTalent;
use common\models\djgenre\DjGenre;

$this->title = 'Wallet';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box">
    <div class="box-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Referral Name</th>
                    <th>Referral Code</th>
                    <th>Amount</th>
                    <th>Transaction Id</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($funds) {
                    $total_amount = 0;
                    foreach ($funds as $fund) {
                        $total_amount += $fund['amount'];
                        ?>
                        <tr>
                            <td><?= date('d/M/Y h:i', $fund['created_at']) ?></td>
                            <td><?= $fund['type'] ?></td>
                            <td><?= $fund['referral_user'] ?></td>
                            <td><?= $fund['referral_code'] ?></td>
                            <td><strong>$ <?= $fund['amount'] ?></strong></td>
                            <td><?= $fund['transaction_id'] ?></td>
                        </tr>
                        <?php
                    }
                }
                ?> 
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3"></th>
                    <th colspan="1">Amount</th>
                    <th colspan="2">$ <?= $total_amount ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
