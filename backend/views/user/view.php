<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
switch ($model->role) {
    case 5: $this->title  = "Anonymous " . $model->id;
        break;
    case 2: $this->title  = "Raspberry " . $model->id;
        break;
    default:
        $this->title = $model->username;
}
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            'email_confirm_token:email',
            'roleName',
            'phone_number',
            'statusName',
//            'allowance',
//            'timestamp:datetime',
            'created_at',
            'updated_at',
        ],
    ]) ?>



</div>
