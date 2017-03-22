<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;


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
    <h2><?= Html::encode("Relatives")?></h2>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'user.username',
            [
                'label' => 'Resident Name',
                'attribute' => 'resident_id',
                'format' => 'html',
                'value' => function($model){
                    return Html::a($model->resident->fullname, ['/resident/view', 'id' => $model->resident->id]);
                }
            ],
            'relation',
            'created_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Action',
                'template' => '{update}  {delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        $url = \yii\helpers\Url::to(['user-resident/delete', 'id' => $model->id]);
//                        return Html::a('<span class="fa fa-eye"></span>', $url, ['title' => 'view']);
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure to delete this item?'),
                            'data-method' => 'post',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        $url = \yii\helpers\Url::to(['user-resident/update', 'id' => $model->id]);
//                        return Html::a('<span class="fa fa-eye"></span>', $url, ['title' => 'view']);
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('yii', 'Update'),
                            'data-method' => 'post',
                        ]);
                    },
                ]

            ],
        ],
    ]);
    ?>
    <?=Html::a('Add relative', ['user-resident/create'],['class' => 'btn btn-success'])?>
</div>
