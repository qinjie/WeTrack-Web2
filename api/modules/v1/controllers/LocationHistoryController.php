<?php
/**
 * Created by PhpStorm.
 * User: qj
 * Date: 28/3/15
 * Time: 23:28
 */

namespace api\modules\v1\controllers;

use api\common\models\LocationHistory;
use api\components\CustomActiveController;
use common\components\AccessRule;
use common\models\Locator;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\web\UnauthorizedHttpException;
use Yii;
use common\components\Utils;

class LocationHistoryController extends CustomActiveController
{
    public $modelClass = 'api\common\models\LocationHistory';

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

    public function actionNew(){
        $request = Yii::$app->getRequest();
        $beacon_id =$request->getBodyParam('beacon_id');
        $user_id =$request->getBodyParam('user_id');
        $user = User::findOne($user_id);
        if (!$user) return [
            'result' => 'wrong',
            'message' => 'User not found'
        ];
        $locator  = Locator::findOne(['serial_number' => $user->username]);
        if (!$locator) return [
            'result' => 'wrong',
            'message' => 'Locator not found'
        ];
        $location = new LocationHistory();
        $location->user_id = $user_id;
        $location->beacon_id = $beacon_id;
        $location->latitude = $locator->latitude;
        $location->longitude = $locator->longitude;
        $location->address = $locator->location_subname;
        if ($location->save()) return $location;
        else return null;
    }

    /**
     * @return false|null|string
     */
    public function actionAlive()
    {
        $request = Yii::$app->getRequest();
        $status =$request->getBodyParam('status');
        $user_id =$request->getBodyParam('user_id');
        $user = User::findOne($user_id);
        if (!$user) return [
            'result' => 'wrong',
            'message' => 'User not found'
        ];
        $user->status = $status;
        $user->updated_at = date("Y-m-d H:i:s");
        $user->save();
        return "ok";
    }
}