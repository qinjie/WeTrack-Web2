<?php
/**
 * Created by PhpStorm.
 * User: zqi2
 * Date: 30/1/2016
 * Time: 12:25 PM
 */

namespace common\models\auth;


use Yii;
use yii\rbac\Rule;

class CreatorRule extends Rule
{
    const name = 'isCreator';

    # $user: ID of current login user
    public function execute($userId, $item, $params)
    {
        if (isset($params['model'])) {
            # if the model to be used is directly specified
            $model = $params['model'];
        } else {
            # Note: this assume that your URL structure contains id of the model
            $id = Yii::$app->request->get('id');
            $model = Yii::$app->controller->findModel($id);
        }

        if ($model)
            return $model->user_id == $userId;

        return false;
    }
}