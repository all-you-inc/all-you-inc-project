<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\auth\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use common\models\usertalent\UserTalent;
use common\models\djgenre\DjGenre;

//dd(\Yii::$app->user->identity->getUser());

$this->title = 'Notification';
$this->params['breadcrumbs'][] = $this->title;
?>
<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container-fluid">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= \yii\helpers\Url::home() ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
            </ol>
        </div><!-- End .container-fluid -->
    </nav>
    <div class="container">
        <?= $this->render('../layouts/shared/admin_menu', ['active' => 'notification', 'url' => Html::encode(Url::to(['/myprofile']))]) ?>
        <div class="col-lg-9 order-lg-last dashboard-content">
            <!--<h2><?= $this->title ?></h2>-->
            <?= Html::beginForm() ?>
            <div class="row">
                <div class="container">
                    <h2>NOTIFICATION SETTING</h2>
                    <table class="table table-step-shipping">
                        <thead>
                        <th>
                        <td><strong>Event</strong></td>
                        <td><strong>Email</strong></td>
                        <td><strong>Web</strong></td>
                        <td><strong>Mobile</strong></td>
                        </th>
                        </thead>
                        <tbody> 
                            <?php
                            if (isset($events) && $events != NULL) {
                                $count = 1;
                                foreach ($events as $event) {
                                    $user_event = [];
                                    if ($user_events) {
                                        foreach ($user_events as $item) {
                                            $user_event = $item->notification_event_id == $event->id ? $item : NULL;
                                            if ($user_event)
                                                break;
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $count ?></td>
                                        <td><strong><?= $event->event ?></strong></td>
                                        <td><input type="checkbox" <?php echo $user_event != NULL && $user_event->email == TRUE ? 'checked=""' : '' ?> name="events[<?= $event->id ?>][email]" class="basic"></td>
                                        <td><input type="checkbox" <?php echo $user_event != NULL && $user_event->web == TRUE ? 'checked=""' : '' ?> name="events[<?= $event->id ?>][web]" class="basic"></td>
                                        <td><input type="checkbox" <?php echo $user_event != NULL && $user_event->mobile == TRUE ? 'checked=""' : '' ?> name="events[<?= $event->id ?>][mobile]" class="basic"></td>
                                    </tr>
                                    <?php
                                    $count++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div><!-- End .row -->
            <div class="form-footer">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'notification-button']) ?>
            </div>
            <?= Html::endForm() ?> 
        </div>
    </div>
</main><!-- End .main -->