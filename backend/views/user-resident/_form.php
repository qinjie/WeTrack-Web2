<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Resident;


/* @var $this yii\web\View */
/* @var $model common\models\UserResident */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-resident-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'user_id')->dropDownList(
        ArrayHelper::map(\common\models\User::find()->all(), 'id', 'username')
    )
    ?>

    <?= $form->field($model, 'resident_id')->dropDownList(
        ArrayHelper::map(Resident::find()->all(), 'id', 'fullname'))
    ?>

<!--    --><?//= $form->field($model, 'user_id')->textInput() ?>

<!--    --><?//= $form->field($model, 'resident_id')->textInput() ?>
    <?php
    $data = [
        'parent'=> 'parent',
        'cousin' => 'cousin',
        'son'=> 'son',
        'daughter'=>'daughter',
        'brother'=>'brother',
        'sister'=>'sister',
        'grand parent' => 'grand parent',
        'others'=>'others'
    ];
    ?>
    <?= $form->field($model, 'relation')->dropDownList($data) ?>

<!--    --><?//= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
