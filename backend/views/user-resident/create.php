<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\UserResident */

$this->title = 'Create relationship';
//$this->params['breadcrumbs'][] = ['label' => 'Relations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-resident-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id
    ]) ?>

</div>
