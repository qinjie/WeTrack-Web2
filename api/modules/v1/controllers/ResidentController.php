<?php
/**
 * Created by PhpStorm.
 * User: qj
 * Date: 28/3/15
 * Time: 23:28
 */

namespace api\modules\v1\controllers;

use api\common\models\Location;
use api\common\models\Resident;
use api\components\CustomActiveController;
use common\components\AccessRule;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\web\UnauthorizedHttpException;
use Yii;

class ResidentController extends CustomActiveController
{
    public $modelClass = 'api\common\models\Resident';

    // Return all missing residents
    // Use ActiveDataProvider so that 'expand' feature can be used
    public function actionMissing()
    {
        return new ActiveDataProvider([
            'query' => Resident::find()->where(['status' => 1])
        ]);
    }
    public function behaviors() {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' => [],
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'ruleConfig' => [
                'class' => AccessRule::className(),
            ],
            'rules' => [
                [
                    'actions' => [],
                    'allow' => true,
                    'roles' => ['?'],
                ],
                [
                    'actions' => [],
                    'allow' => true,
                    'roles' => ['@'],
                ]
            ],
            'denyCallback' => function ($rule, $action) {
                throw new UnauthorizedHttpException('You are not authorized');
            },
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
            ],
        ];

        return $behaviors;
    }
    public function deleteLocation($locations){
        foreach ($locations as $key => $value){
            $location = Location::findOne($value->id);
            $location->delete();
        }
    }
    public function actionStatus(){
        $request = Yii::$app->getRequest();
        $id =$request->getBodyParam('id');
        $model = Resident::findOne($id);
        $model->status = 1 - $model->status;
        $this->deleteLocation($model->locations);
        if ($model->status == 1) {
            $model->reported_at = date('Y-m-d H:i:s');
        }
        else {
            $model->reported_at = "";
        }
        $model->save();
        return $model;
    }

}