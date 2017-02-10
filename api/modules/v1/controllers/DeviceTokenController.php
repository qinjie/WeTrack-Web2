<?php
/**
 * Created by PhpStorm.
 * User: tungphung
 * Date: 10/2/17
 * Time: 9:47 AM
 */

namespace api\modules\v1\controllers;



use api\components\CustomActiveController;
use yii\rest\ActiveController;

class DeviceTokenController extends CustomActiveController
{
    public $modelClass = 'common\models\DeviceToken';


}