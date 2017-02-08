<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
/* @var $roleArray array */
$model->status = isset($model->status) ? $model->status : 1;
?>

<div class="user-form">
<!--    40: admin, 20: family, 10: volunteer:-->
    <?php
        $roleArray = [];
        if (Yii::$app->user->identity->role >= 20) $roleArray += [10 => 'Volunteer'];
        if (Yii::$app->user->identity->role >= 30) $roleArray += [20 => 'Family'];
        if (Yii::$app->user->identity->role >= 40) $roleArray += [40 => 'Admin'];
    ?>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->widget(\kartik\select2\Select2::classname(), [
        'data' => ['0' => 'Deleted', '1' => 'Blocked', '5' => 'Waiting', '10' => 'Active'],
        'options' => ['placeholder' => 'Select status ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'role')->widget(\kartik\select2\Select2::classname(), [
        'data' => $roleArray,
        'options' => ['placeholder' => 'Select role ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
