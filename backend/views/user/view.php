<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = $model->username;
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
            [
                'attribute'=>'username',
                'value'=>function ($data){
                    if ($data->role == 5) return "Anonymous " . $data->id;
                    if ($data->role == 2) return "Raspberry " . $data->id;
                    return $data->username;
                }
            ],
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
