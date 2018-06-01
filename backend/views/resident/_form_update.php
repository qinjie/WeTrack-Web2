<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model common\models\Resident */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="resident-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dob')->widget(\kartik\date\DatePicker::classname(), [
        'options' => ['placeholder' => 'Enter birth date ...'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy/mm/dd'
        ]
    ]);?>


    <?= $form->field($model, 'nric')->textInput(['maxlength' => true]) ?>
    <?php
    if ($model->isNewRecord) {
        echo $form->field($model, 'file')->widget(\kartik\file\FileInput::className(),
            [
                'options' => ['accept' => 'image/*'],
                'pluginOptions' => [
                    'showUpload' => false,
                    //                'overwriteInitial'=>false,
                    'maxFileSize'=>2800
                ]
            ]);
    }
    else {
        echo $form->field($model, 'file')->widget(\kartik\file\FileInput::className(),
            [
                'options' => ['accept' => 'image/*'],
                'pluginOptions' => [
                    'showUpload' => false,
                    'initialPreview'=> "../../" . $model->image_path,
                    'initialPreviewAsData'=>true,
//                'overwriteInitial'=>false,
                    'maxFileSize'=>2800
                ]
            ]
        );
    }
    ?>


    <?php $data = [
        '0' => 'Available',
        '1' => 'Missing'
    ];
    $hide = [
        '0' => 'No',
        '1' => 'Yes'
    ];
    ?>
    <?= $form->field($model, 'status')->dropDownList($data) ?>
    <?= $form->field($model, 'hide_photo')->dropDownList($hide)?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
