<?php
/**
 * Created by PhpStorm.
 * User: tungphung
 * Date: 10/2/17
 * Time: 9:47 AM
 */

namespace api\modules\v1\controllers;



use api\common\models\UserToken;
use api\components\CustomActiveController;
use common\components\TokenHelper;
use common\models\DeviceToken;
use common\models\User;
use yii\rest\ActiveController;
use Yii;

class DeviceTokenController extends CustomActiveController
{
    public $modelClass = 'api\common\models\DeviceToken';

    public function actionNew(){
        $request = Yii::$app->getRequest();
        $user_id =$request->getBodyParam('user_id');
        $token =$request->getBodyParam('token');
        $wrong =[
            "result"=> "wrong"
        ];
        if($user_id == 0) {
//            return "ha";
            $user = User::findOne(['username' => $token]);

            if (!$user) {
                $user = $this->createUser($token);
                // Store a new Device Token
                $device_token= $this->createDeviceToken($user->id, $user->username);
            }
            $device_token = DeviceToken::findOne(['user_id' => $user->id, 'token' => $token]);
            if (!$device_token) $device_token= $this->createDeviceToken($user->id, $user->username);

            $user_id = $user->id;

            //Store a new Token
//            return $user;
            UserToken::deleteAll(['user_id' => $user_id]);
            $token = TokenHelper::createUserToken($user_id);
            return [
                'result' => "correct",
                'user_id' => $user->id,
                'username' => $user->username,
                'token' => $token->token,
                'email' => "",
                'role' => $user->role,
//                'device_token' => $device_token->token
            ];
        }
        else {
            $user = User::findOne($user_id);
            if (!$user) return $wrong;
            $device_token = DeviceToken::findOne(['user_id' => $user->id, 'token' => $token]);
            if (!$device_token) $device_token= $this->createDeviceToken($user->id, $token);;
            return [
                'result' => "correct",
                'user_id' => $user->id,
                'username' => $user->username,
                'device_token' => $device_token->token
            ];
        }



    }
    public function createUser($token){
        $user = new User();
        $user->username = $token;
        $user->role = 5;
        $user->status = 10;
        if ($user->save()) return $user;
        else {
//            var_dump($user);
            return null;
        }
    }

    public function createDeviceToken($user_id, $token){
        $device_token = new DeviceToken();
        $device_token->user_id = $user_id;
        $device_token->token = $token;

        if ($device_token->save()) return $device_token;
        else return null;
    }

    public function actionDel(){
        $request = Yii::$app->getRequest();
        $user_id =$request->getBodyParam('user_id');
        $token =$request->getBodyParam('token');
        $device = DeviceToken::findOne(['user_id' => $user_id, 'token' => $token]);
        if($device) {
            $device->delete();
            return ['result' => 'Done'];
        }
        else return ['result' => 'Error'];

    }


}