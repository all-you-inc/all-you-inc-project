<?php

use mihaildev\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $product shop\entities\Shop\Product\Product */
/* @var $model shop\forms\manage\Shop\Product\ProductEditForm */

$this->title = 'Update Product: ' . $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['view', 'id' => $product->id]];
$this->params['breadcrumbs'][] = 'Update';
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
    <div class="container container user-view">
        <h2>
            <button class="btn btn-primary" style="float: right;margin: -12px 0;" onclick="history.go(-1);">Back </button>
        </h2>
    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border"><h2>Product Detail</h2></div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'talent_id')->dropDownList($model->talentList()) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <?= $form->field($model, 'description')->widget(CKEditor::className()) ?>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border"><h2>Warehouse</h2></div>
        <div class="box-body">
            <?= $form->field($model, 'weight')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border"><h2>Categories</h2></div>
                <div class="box-body">
                    <?= $form->field($model->categories, 'main')->dropDownList($model->categories->categoriesList(), ['prompt' => '']) ?>
                    <?= $form->field($model->categories, 'others')->checkboxList($model->categories->categoriesList()) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border"><h2>Tags</h2></div>
                <div class="box-body">
                    <?= $form->field($model->tags, 'existing')->checkboxList($model->tags->tagsList()) ?>
                    <?= $form->field($model->tags, 'textNew')->textInput() ?>
                </div>
            </div>
        </div>
    </div>

    <div class="box box-default">
        <!-- <div class="box-header with-border">Characteristics</div> -->
        <div class="box-body">
            <?php foreach ($model->values as $i => $value): ?>
                <?php if ($variants = $value->variantsList()): ?>
                    <?= $form->field($value, '[' . $i . ']value')->hiddenInput($variants, ['prompt' => '','multiple'=>'multiple']) ?>
                <?php else: ?>
                    <?= $form->field($value, '[' . $i . ']value')->hiddenInput() ?>
                <?php endif ?>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="box box-default">
        <!-- <div class="box-header with-border">SEO</div> -->
        <div class="box-body">
            <?= $form->field($model->meta, 'title')->hiddenInput()->label(false) ?>
            <?= $form->field($model->meta, 'description')->hiddenInput(['rows' => 2])->label(false) ?>
            <?= $form->field($model->meta, 'keywords')->hiddenInput()->label(false) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<main>
