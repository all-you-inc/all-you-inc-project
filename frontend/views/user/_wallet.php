<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\auth\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

                    <?php if ($funds) { ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Referral Name</th>
                                    <th>User Name</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $total_amount = 0;
                                    foreach ($funds as $fund) {
                                        $total_amount += $fund['amount'];
                                        ?>
                                    <tr>
                                        <td><?= date('d/M/Y h:i', $fund['created_at']) ?></td>
                                        <td><?= $fund['type'] ?></td>
                                        <td><?= $fund['name'] ?></td>
                                        <td><?= $fund['referral_user'] ?></td>
                                        <td><?= $fund['user_name'] ?></td>
                                        <td><strong>$ <?= $fund['amount'] ?></strong></td>
                                    </tr>
                                <?php
                                    }
                                    ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4">
                                    </th>
                                    <th colspan="1">Total Amount</th>
                                    <th colspan="1">$ <?= $total_amount ?></th>
                                </tr>
                                <tr>
                                    <th colspan="4">
                                    </th>
                                    <th colspan="1">Grand Amount</th>
                                    <th colspan="1">$ <?= $total ?></th>
                                </tr>
                                <tr>
                                    <th colspan="4">
                                    </th>
                                    <th colspan="2">
                                        <?=
                                                LinkPager::widget([
                                                    'pagination' => $pages,
                                                    'activePageCssClass' => 'page-item active',
                                                    'maxButtonCount' => 8,
                                                    'linkOptions' => ['class' => 'page-item page-link'],
                                                    'disabledPageCssClass' => 'disabled',
                                                ]);
                                            ?>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    <?php } else {
                        echo 'No Record Found!!';
                    }
                    ?>