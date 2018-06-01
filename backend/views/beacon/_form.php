<?php

use common\models\Beacon;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Beacon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="beacon-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uuid')->textInput() ?>

    <?= $form->field($model, 'major')->textInput() ?>

    <?= $form->field($model, 'minor')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(Beacon::GetStatusArray()) ?>

    <?= $form->field($model, 'resident_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(\common\models\Resident::find()->all(), 'id', 'fullname'),
        'options' => ['placeholder' => 'Select a resident...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
