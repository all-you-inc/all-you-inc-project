<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use common\models\userprofileimage\UserProfileImage;
use yii\widgets\LinkPager;

$this->title = 'message';
$this->params['breadcrumbs'][] = $this->title;
?>
<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container-fluid">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
            </ol>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <h1 style="text-align: center;"><?= isset($thread->title) ? $thread->title : 'New Message Thread' ?></h1>
                <article class="entry single">
                    <div class="entry-body" style="border-bottom: white;">
                        <?php if (!isset($_GET['thread-id'])) { ?>
                            <div class="comment-respond">
                                <?= Html::beginForm() ?>
                                <div class="form-group required-field">
                                    <label>User</label>
                                    <select class="form-control" name="Message[user_id]" required>
                                        <option value=""><?= 'Please Select Any User' ?></option>         
                                        <?php
                                        if ($users) {
                                            foreach ($users as $user) {
                                                ?>
                                                <option <?= isset($_GET['user-id']) && $_GET['user-id'] == $user->id ? 'selected' : '' ?> value="<?= $user->id ?>"><?= isset($user->name) ? $user->name : 'Not Set' ?></option>         
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group required-field">
                                    <label>Title</label>
                                    <input class="form-control" type="text" name="Message[title]" required>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea cols="10" rows="1" class="form-control" name="Message[description]"></textarea>
                                </div>
                                <div class="form-group required-field">
                                    <label>Message</label>
                                    <textarea cols="10" rows="1" class="form-control" name="Message[body]" required></textarea>
                                </div>
                                <input type="hidden" name="Message[creator_id]" value="<?= \Yii::$app->user->id ?>">
                                <div class="form-footer">
                                    <?= Html::submitButton('Send', ['class' => 'btn btn-primary', 'name' => 'submit', 'value' => 'thread']) ?>
                                </div>
                                <?= Html::endForm() ?>
                            </div>                       
                        <?php } ?>
                        <?php
                        if (isset($messages['data']) && $messages['data'] != NULL) {
                            foreach ($messages['data'] as $message) {
                                $date = isset($message->created_at) ? date('l jS \of F Y h:i:s A', $message->created_at) : '-:-:-';
                                $image = UserProfileImage::getProfileImage($message->user->id);
                                ?>
                                <div class="entry-author <?= isset($_GET['message-id']) && $_GET['message-id'] == $message->id ? 'active-noti' : '' ?>">
                                    <figure style="max-width: 50px;max-height: 50px;margin-bottom: 1.5rem;">
                                        <img src="<?= ($image == null) ? Yii::getAlias('@web/images/spotlight/profile-1.png') : $image ?>" alt="author" >
                                    </figure>
                                    <h2 style="margin: 0;">&nbsp;<?= $message->user->id == \Yii::$app->user->id ? 'You' : $message->user->name ?></h2>
                                    <span><i class="icon-calendar"></i><?= $date ?></span>
                                    <div class="author-content" style="margin-top: 10px;">
                                        <p><?= $message->body ?></p>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                        <?php
                        if (isset($messages['pages']) && $messages['pages'] != NULL) {
                            echo LinkPager::widget([
                                'pagination' => $messages['pages'],
                                'activePageCssClass' => 'page-item active',
                                'maxButtonCount' => 8,
                                'linkOptions' => ['class' => 'page-item page-link'],
                                'disabledPageCssClass' => 'disabled',
                            ]);
                        }
                        ?>
                    </div><!-- End .entry-body -->
                </article><!-- End .entry -->
            </div><!-- End .col-lg-9 -->

            <aside class="sidebar col-lg-3">
                <div class="sidebar-wrapper">
                    <?php if (isset($messages['data']) && $messages['data'] != NULL) { ?>
                        <div class="widget widget-search">
                            <form role="search" method="get" class="search-form">
                                <?php
                                foreach ($_GET as $name => $value) {
                                    if ($name != 'keyword') {
                                        $name = htmlspecialchars($name);
                                        $value = htmlspecialchars($value);
                                        echo '<input type="hidden" name="' . $name . '" value="' . $value . '">';
                                    }
                                }
                                ?>
                                <input type="search" value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>" class="form-control" placeholder="Search posts here..." name="keyword">
                                <button type="submit" class="search-submit" title="Search">
                                    <i class="icon-search"></i>
                                    <span class="sr-only">Search</span>
                                </button>
                            </form>
                        </div>
                        <?php
                    } if (isset($threads['data']) && $threads['data'] != NULL) {
                        $url = Html::encode(Url::to(['/messages']));
                        $url .= isset($_GET['user-id']) ? '?user-id=' . $_GET['user-id'] : '';
                        ?>
                        <div class="widget widget-categories">
                            <h4 class="widget-title display-inline-block">All Threads</h4>
                            <a href="<?= $url ?>"><button class="float-right">New Thread</button></a>
                            <ul class="list">
                                <?php
                                foreach ($threads['data'] as $thread) {
                                    $url = Html::encode(Url::to(['/messages']));
                                    $url .= isset($_GET['user-id']) ? '?user-id=' . $_GET['user-id'] . '&' : '';
                                    $url .= 'thread-id=' . $thread['id'];
                                    ?>                            
                                    <li <?= isset($_GET['thread-id']) && $_GET['thread-id'] == $thread['id'] ? 'class="active"' : '' ?> ><a href="<?= $url ?>"><?= $thread['title'] ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                    <!------ Reply Start ------>
                    <?php if (isset($_GET['thread-id'])) { ?>
                        <div class="comment-respond">
                            <h3>Leave a Reply</h3>
                            <div class="comment-respond">
                                <?= Html::beginForm() ?>
                                <div class="form-group required-field">
                                    <label>Message</label>
                                    <textarea cols="30" rows="1" class="form-control" name="Message[body]" required></textarea>
                                    <input type="hidden" name="Message[thread_id]" value="<?= $_GET['thread-id'] ?>">
                                    <input type="hidden" name="Message[user_id]" value="<?= \Yii::$app->user->id ?>">
                                </div>
                                <div class="form-footer">
                                    <?= Html::submitButton('Send', ['class' => 'btn btn-primary', 'name' => 'submit', 'value' => 'message']) ?>
                                </div>
                                <?= Html::endForm() ?>
                            </div>                    
                        </div>
                    <?php } ?>
                    <!------ Reply End ------>
                </div><!-- End .sidebar-wrapper -->
            </aside><!-- End .col-lg-3 -->
        </div><!-- End .row -->
    </div><!-- End .container -->

    <div class="mb-6"></div><!-- margin -->
</main>