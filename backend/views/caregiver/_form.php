<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Resident;
use common\models\Relative;

/* @var $this yii\web\View */
/* @var $model common\models\Caregiver */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="caregiver-form">

    <?php
    $form = ActiveForm::begin();
    ?>

    <?= $form->field($model, 'relative_id')
        ->dropDownList(
            ArrayHelper::map(Relative::find()->all(), 'id', 'fullname')
        )->label("Relative Name") ?>

    <?= $form->field($model, 'resident_id')
        ->dropDownList(
            ArrayHelper::map(Resident::find()->all(), 'id', 'fullname')
        )->label('Resident Name')
    ?>

    <?php
    $data = [
        'parent' => 'parent',
        'cousin' => 'cousin',
        'son' => 'son',
        'daughter' => 'daughter',
        'brother' => 'brother',
        'sister' => 'sister',
        'grand parent' => 'grand parent',
        'others' => 'others'
    ];
    ?>

    <?= $form->field($model, 'relation')->textInput()->label("Relationship") ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
