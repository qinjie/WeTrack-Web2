<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CaregiverSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Relation';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="caregiver-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Relation', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Caregiver',
                'attribute' => 'relative_id',
                'format' => 'html',
                'value' => function($model){
                    return Html::a($model->relative->fullname, ['/relative/view', 'id' => $model->relative->id]);
                }
            ],
            [
              'label' => 'Resident',
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
