<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\auth\SignupForm */

use yii\helpers\Html;
//use kartik\form\ActiveForm;
?>
<?= Html::beginForm() ?>
<div class="form-group required-field">
    <label>First Name </label>
    <input type="text" name="first_name" class="form-control" value="<?= isset($model->first_name) ? $model->first_name : '' ?>" required>
</div>
<div class="form-group required-field">
    <label>Last Name </label>
    <input type="text" name="last_name" class="form-control" value="<?= isset($model->last_name) ? $model->last_name : '' ?>" required>
</div>
<div class="form-group required-field">
    <label>Phone Number </label>
    <input type="text" name="phone_number" class="form-control" value="<?= isset($model->phone_number) ? $model->phone_number : '' ?>" required>
</div>
<div class="form-group required-field">
    <label>Address </label>
    <input type="text" name="address" class="form-control" value="<?= isset($model->address) ? $model->address : '' ?>" required>
</div>
<div class="form-group required-field">
    <label>Country </label>
    <div class="select-custom">
        <select class="form-control" required="" name="country_id">
            <option value="">Please Select Any Country</option>
            <?php
            if ($countries) {
                foreach ($countries as $country) {
                    ?>
                    <option <?= isset($model->country_id) && $model->country_id == $country->id ? 'selected' : '' ?> value="<?= $country->id ?>"><?= $country->title ?></option>
                    <?php
                }
            }
            ?>
        </select>
    </div>
</div> 
<div class="form-group required-field">
    <label>State </label>
    <input type="text" name="state" class="form-control" value="<?= isset($model->state) ? $model->state : '' ?>" required>
</div>
<div class="form-group required-field">
    <label>City </label>
    <input type="text" name="city" class="form-control" value="<?= isset($model->city) ? $model->city : '' ?>" required>
</div>
<div class="form-group required-field">
    <label>Area </label>
    <input type="text" name="area" class="form-control" value="<?= isset($model->area) ? $model->area : '' ?>" required>
</div>
<div class="form-group required-field">
    <label>Postal Code </label>
    <input type="text" name="postal_code" class="form-control" value="<?= isset($model->postal_code) ? $model->postal_code : '' ?>" required>
</div>
<div class="form-group">
    <label><input type="checkbox" value="1"  <?php if ($model->default == 1) echo 'checked'; ?> name="default"> Make it Default Address</label>
</div>
<div class="form-footer">
    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Save', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
</div> 
<?= Html::endForm() ?>
