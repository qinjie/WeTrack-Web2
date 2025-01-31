<?php
/**
 * Created by PhpStorm.
 * User: tungphung
 * Date: 18/1/17
 * Time: 10:41 AM
 */

namespace api\modules\v1\controllers;


use api\components\CustomActiveController;
use common\models\Beacon;
use common\models\Resident;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use common\components\AccessRule;
use yii\filters\VerbFilter;
use yii\web\UnauthorizedHttpException;


class BeaconController extends CustomActiveController
{
    public $modelClass = 'api\common\models\Beacon';

    // Returns all active beacons of missing residents
    // Use ActiveDataProvider so that 'expand' feature can be used
    public function actionActive()
    {
        return new ActiveDataProvider([
            'query' => Beacon::find()->joinWith('resident')->where(['resident.status' => 1, 'beacon.status' => 1])
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

}