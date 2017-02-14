<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserResidentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Relations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-resident-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create relation', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'user.username',
//            [
//                'label' => 'User Name',
//                'attribute' => 'user_id',
//                'format' => 'html',
//                'value' => function($model){
//                    return Html::a($model->user->fullname, ['/user/view', 'id' => $model->resident->id]);
//                }
//            ],

            [
                'label' => 'Resident Name',
                'attribute' => 'resident_id',
                'format' => 'html',
                'value' => function($model){
                    return Html::a($model->resident->fullname, ['/resident/view', 'id' => $model->resident->id]);
                }
            ],
            'relation:ntext',
            'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
