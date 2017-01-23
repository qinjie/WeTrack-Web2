<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \common\models\form\ChangeEmailForm */

$this->title = 'Change Email';
$this->params['breadcrumbs'][] = ['label' => 'Account', 'url' => ['account']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-account-email-change">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="user-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'update-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
