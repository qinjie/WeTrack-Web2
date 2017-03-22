<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php
        $roleArray = [];
        if (Yii::$app->user->identity->role >= 5) $roleArray += [5 => 'Volunteer'];
        if (Yii::$app->user->identity->role >= 20) $roleArray += [20 => 'Family'];
        if (Yii::$app->user->identity->role >= 40) $roleArray += [40 => 'Admin'];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',

            [
                'attribute'=>'username',
                'value'=>function ($data){
                    if ($data->role == 5) return "Anonymous " . $data->id;
                    if ($data->role == 2) return "Raspberry " . $data->id;
                    return $data->username;
                }
            ],
            // 'auth_key',
            // 'password_hash',
            // 'access_token',
            // 'password_reset_token',
            'email:email',
            [
                'attribute'=>'status',
                'value'=>'statusName',
//                'filter'=>[0 => 'Deleted', 10 => 'Active'],
            ],
            [
                'attribute'=>'role',
                'value'=>'roleName',
                'filter'=>$roleArray,
            ],
            // 'email_confirm_token:email',
            // 'allowance',
            // 'timestamp:datetime',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
