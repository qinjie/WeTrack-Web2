<?php
/**
 * Created by PhpStorm.
 * User: tungphung
 * Date: 16/2/17
 * Time: 10:49 AM
 */

namespace api\modules\v1\controllers;

use api\common\models\UserToken;
use common\components\TokenHelper;
use common\models\Locator;
use common\models\User;
use Yii;
use api\common\controllers\CustomActiveController;

class LocatorController extends CustomActiveController
{
    public $modelClass = 'common\models\Locator';
    public function actionLogin(){
        $request = Yii::$app->getRequest();
        $serial_number =$request->getBodyParam('serial_number');
        $locator = Locator::findOne(['serial_number' => $serial_number]);
        $user = User::findOne(['username' => $serial_number]);
        if (!$user) $user = $this->createUser($serial_number);
        if (!$locator) return [
            'result' => 'wrong',
            'message' => 'Locator not found'
        ];
        UserToken::deleteAll(['user_id' => $user->id]);
        $token = TokenHelper::createUserToken($user->id);
        return [
            'result' => "correct",
            'token' => $token->token,
            'user_id' => $user->id

        ];
//        return $locator;
    }
    public function createUser($token){
        $user = new User();
        $user->username = $token;
        $user->role = 2;
        $user->status = 10;
        if ($user->save()) return $user;
        else {
            var_dump($user);
            return null;
        }
    }

}