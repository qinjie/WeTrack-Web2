<?php
/**
 * Created by PhpStorm.
 * User: qj
 * Date: 28/3/15
 * Time: 23:28
 */

namespace api\modules\v1\controllers;

use api\common\models\Resident;
use api\components\CustomActiveController;
use common\components\AccessRule;
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

}