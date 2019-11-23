<?php

use kartik\file\FileInput;
use shop\entities\Shop\Product\Modification;
use shop\entities\Shop\Product\Value;
use shop\helpers\PriceHelper;
use shop\helpers\ProductHelper;
use shop\helpers\WeightHelper;
use yii\bootstrap\ActiveForm;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $product shop\entities\Shop\Product\Product */
/* @var $photosForm shop\forms\manage\Shop\Product\PhotosForm */
/* @var $modificationsProvider yii\data\ActiveDataProvider */

$this->title = $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<main>
    
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <div class="container-fluid">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= \yii\helpers\Url::home() ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
                </ol>
            </div><!-- End .container-fluid -->
        </nav>
    <div class="container  user-view">
        <h2>
            <?= $this->title ?>
            <button class="btn btn-primary" style="float: right;margin: -12px 0;" onclick="history.go(-1);">Back </button>
        </h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border"><h2>Product Details</h2></div>
                    <div class="box-body">
                        <?= DetailView::widget([
                            'model' => $product,
                            'attributes' => [
                                'id',
                                [
                                    'attribute' => 'status',
                                    'value' => ProductHelper::statusLabel($product->status),
                                    'format' => 'raw',
                                ],
                                'code',
                                'name',
                                [
                                    'attribute' => 'Category',
                                    'value' => ArrayHelper::getValue($product, 'category.name'),
                                ],
                                [
                                    'label' => 'Other categories',
                                    'value' => implode(', ', ArrayHelper::getColumn($product->categories, 'name')),
                                ],
                                [
                                    'attribute' => 'Talent',
                                    'value' => ArrayHelper::getValue($product, 'userTalent.talent.name'),
                                ],
                                [
                                    'label' => 'Tags',
                                    'value' => implode(', ', ArrayHelper::getColumn($product->tags, 'name')),
                                ],
                                'quantity',
                                [
                                    'attribute' => 'weight',
                                    'value' => WeightHelper::format($product->weight),
                                ],
                                [
                                    'attribute' => 'price_new',
                                    'value' => PriceHelper::format($product->price_new),
                                ],
                                [
                                    'attribute' => 'price_old',
                                    'value' => PriceHelper::format($product->price_old),
                                ],
                            ],
                        ]) ?>
                        <br />
                        <p>
                            <?php if ($product->isActive()): ?>
                                <?= Html::a('Draft', ['draft', 'id' => $product->id], ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
                            <?php else: ?>
                                <?= Html::a('Activate', ['activate', 'id' => $product->id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>
                            <?php endif; ?>
                            <?= Html::a('Update', ['update', 'id' => $product->id], ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('Change Price', ['price', 'id' => $product->id], ['class' => 'btn btn-primary']) ?>
                            <?php if ($product->canChangeQuantity()): ?>
                                <?= Html::a('Change Quantity', ['quantity', 'id' => $product->id], ['class' => 'btn btn-primary']) ?>
                            <?php endif; ?>
                            <?= Html::a('Delete', ['delete', 'id' => $product->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border"><h2>Description</h2></div>
            <div class="box-body">
                <?= Yii::$app->formatter->asHtml($product->description, [
                    'Attr.AllowedRel' => array('nofollow'),
                    'HTML.SafeObject' => true,
                    'Output.FlashCompat' => true,
                    'HTML.SafeIframe' => true,
                    'URI.SafeIframeRegexp'=>'%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
                ]) ?>
            </div>
        </div>


        <div class="box" id="photos">
            <div class="box-header with-border"><h2>Photos</h2></div>
            <div class="box-body">

                <div class="row">
                    <?php foreach ($product->photos as $photo): ?>
                        <div class="col-md-2 col-xs-3" style="text-align: center">
                            <div class="btn-group">
                                <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>', ['move-photo-up', 'id' => $product->id, 'photo_id' => $photo->id], [
                                    'class' => 'btn btn-primary',
                                    'data-method' => 'post',
                                ]); ?>
                                <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete-photo', 'id' => $product->id, 'photo_id' => $photo->id], [
                                    'class' => 'btn btn-danger',
                                    'data-method' => 'post',
                                    'data-confirm' => 'Remove photo?',
                                ]); ?>
                                <?= Html::a('<span class="glyphicon glyphicon-arrow-right"></span>', ['move-photo-down', 'id' => $product->id, 'photo_id' => $photo->id], [
                                    'class' => 'btn btn-primary',
                                    'data-method' => 'post',
                                ]); ?>
                            </div>
                            <div>
                                <?= Html::a(
                                    Html::img($photo->getThumbFileUrl('file', 'thumb')),
                                    $photo->getUploadedFileUrl('file'),
                                    ['class' => 'thumbnail', 'target' => '_blank']
                                ) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php $form = ActiveForm::begin([
                    'options' => ['enctype'=>'multipart/form-data'],
                ]); ?>

                <?= $form->field($photosForm, 'files[]')->label(false)->widget(FileInput::class, [
                    'options' => [
                        'accept' => 'image/*',
                        'multiple' => true,
                    ]
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Upload', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    </div>
</main>