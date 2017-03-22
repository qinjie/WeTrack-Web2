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

    <?php
        $form = ActiveForm::begin();
        if (!empty($id))$model->user_id = $id;
    ?>

    <?= $form->field($model, 'user_id')->dropDownList(
        ArrayHelper::map(\common\models\User::find()->where(['>', 'role', \api\common\models\User::ROLE_ANONYMOUS])->all(), 'id', 'username')
    )->label("Username")
    ?>

    <?= $form->field($model, 'resident_id')->dropDownList(
        ArrayHelper::map(Resident::find()->all(), 'id', 'fullname'))->label('Resident Name')
    ?>

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
    <?= $form->field($model, 'relation')->textInput()->label('Relationship') ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
