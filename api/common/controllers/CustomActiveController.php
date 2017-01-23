<?php
namespace api\common\controllers;

use common\models\User;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class CustomActiveController extends ActiveController
{

    public $modelClass = '';

    # Include envelope
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        //-- Include pagination information directly to simplify the client development work
        'collectionEnvelope' => 'items',
    ];

    # Used by HttpBasicAuth
    public function auth($username, $password)
    {
        // Return Identity object or null
        $user = User::findByUsername($username);
        if ($user && $user->validatePassword($password))
            return $user;
        else
            return null;
    }

    public function actionSearch()
    {
        if (!empty($_GET)) {
            $model = new $this->modelClass;
            foreach ($_GET as $key => $value) {
                if (!$model->hasAttribute($key)) {
                    throw new \yii\web\HttpException(404, 'Invalid attribute:' . $key);
                }
            }
            try {
                $provider = new ActiveDataProvider([
                    'query' => $model->find()->where($_GET),
                    'pagination' => false
                ]);
            } catch (Exception $ex) {
                throw new \yii\web\HttpException(500, 'Internal server error');
            }

            if ($provider->getCount() <= 0) {
                throw new \yii\web\HttpException(404, 'No entries found with this query string');
            } else {
                return $provider;
            }
        } else {
            throw new \yii\web\HttpException(400, 'There are no query string');
        }

    }

    public function findModel($id)
    {
//        if (($model = TestPost::findOne($id)) !== null) {
        if (($model = call_user_func($this->modelClass.'::findOne', $id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}