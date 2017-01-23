<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Locator */

$this->title = 'Create Locator';
$this->params['breadcrumbs'][] = ['label' => 'Locators', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="locator-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
