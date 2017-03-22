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
//        if (Yii::$app->user->identity->role >= 5) $roleArray += [5 => 'Volunteer'];
        if (Yii::$app->user->identity->role >= 20) $roleArray += [20 => 'Family'];
        if (Yii::$app->user->identity->role >= 40) $roleArray += [40 => 'Admin'];
    ?>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model,'status')->dropDownList(['10' => 'Active', '0' => 'Inactive'])?>
    <?= $form->field($model, 'role')->dropDownList($roleArray)?>





    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
