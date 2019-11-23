<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\ContactForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Message Inbox';
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
        <?= $this->render('../layouts/shared/admin_menu', ['active' => '/thread', 'url' => Html::encode(Url::to(['/wallet']))]) ?>
        <div class="col-lg-19 order-lg-last dashboard-content">
            <div class="row">
                <div class="container">
                    <h2 class="display-inline-block"><?= $this->title ?></h2>
                    <a href="<?= Html::encode(Url::to(['/messages'])) ?>"><button class="btn float-right">New Thread</button></a>
                    <table class="table">
                        <thead>
                            <tr>
                                <!--<th>Select</th>-->
                                <th>Title</th>
                                <th>Description</th>
                                <th>Creator</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($threads['data']) && $threads['data'] != NULL) {
                                foreach ($threads['data'] as $thread) {
                                    ?>
                                    <tr>
                                        <td>
                                            <a href="<?= yii\helpers\Url::to(['messages?thread-id=' . $thread['id']]); ?>">
                                                <?php if ($thread['unread_count'] > 0) { ?>
                                                    <span class="undread-msg-count float-right"><?= $thread['unread_count'] ?></span>                                                
                                                <?php } ?>
                                                <strong class="display-inline-block"><?= $thread['title'] ?></strong>
                                            </a>
                                        </td>
                                        <td><?= $thread['description'] ?></td>
                                        <td><?= $thread['creator'] ?></td>
                                        <td><a onclick="return confirm('Are you sure?')" href="<?= yii\helpers\Url::to(['chat/delete?thread_id=' . $thread['id']]); ?>"><button class="custom-red-btn">Delete</button></a></td>                                        
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="float-right">
                        <?php
                        if (isset($threads['pages']) && $threads['pages'] != NULL) {
                            echo LinkPager::widget([
                                'pagination' => $threads['pages'],
                                'activePageCssClass' => 'page-item active',
                                'maxButtonCount' => 8,
                                'linkOptions' => ['class' => 'page-item page-link'],
                                'disabledPageCssClass' => 'disabled',
                            ]);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main><!-- End .main -->
