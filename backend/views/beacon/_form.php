<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Resident;

/* @var $this yii\web\View */
/* @var $model common\models\Beacon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="beacon-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'resident_id')->dropDownList(
        ArrayHelper::map(Resident::find()->all(), 'id', 'fullname'))
    ?>

    <?= $form->field($model, 'uuid')->textInput() ?>

    <?= $form->field($model, 'major')->textInput() ?>

    <?= $form->field($model, 'minor')->textInput() ?>

    <?php $data = [
        '0' => 'Non-Active',
        '1' => 'Active'
    ];
    ?>
    <?= $form->field($model, 'status')->dropDownList($data) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
