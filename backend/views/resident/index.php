<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ResidentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Residents';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resident-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Resident', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'fullname',
            'dob',
            'nric',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'filter' => Html::activeDropDownList($searchModel, 'status',[1 => 'Missing', 0 => 'Available'],['class'=>'form-control','prompt' => 'Select Status']),
                'value' => function($data){
                    $d = ($data->status == 1) ? "checked" : "";
                    $s =
                        '<div class="switch">
                        <input id="' . $data->id . '"class="cmn-toggle cmn-toggle-yes-no" onclick="handleClick(this)" type="checkbox" '. $d .'>
                        <label for="' . $data->id . '"data-on="Missing" data-off="Available"></label>
                     </div>'

                    ;
                    return $s;
                    return ($data->status == 1) ? "Missing" : "Available";

                },
            ],
//            'image_path:ntext',
            // 'thumbnail_path:ntext',
//             'status',
//            [
//                'attribute' => 'status',
//                'value' => function($data){
//                    return ($data->status == 1) ? "Yes" : "No";
//
//                },
//
//            ],

             'created_at',
//            [
//                'filterType' => GridView::FILTER_DATE_RANGE,
//                'filterWidgetOptions' => [
//                    'options' => ['placeholder' => 'Select date'],
//                    'pluginOptions' => [
//                        'format' => 'M-dd-yyyy',
//                        'todayHighlight' => true
//                    ]
//                ],
//                'group' => true,
//                'format' => 'html'
//            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
<script>
    var flag = false;
    function handleClick(cb) {
        if (flag) return;
        flag = true;

        //if (cb.checked)
        //alert(cb.checked);
        var form = $(this);
        $status = (cb.checked)? 0: 1;
        $.ajax({
            url: '../resident/save',
            type: 'post',
            data: {
                id: cb.id,
                status: $status
            },
            success: function (data) {
//                alert(data);
            },
            error: function(jqXHR, errMsg) {
                // handle error
                flag = false;
//                alert(errMsg + $status + jqXHR.status);
            }
        });
    }
</script>
