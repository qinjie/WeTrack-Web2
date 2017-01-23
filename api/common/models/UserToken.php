<?php
namespace api\common\models;

use Yii;

/**
 * This is the model class for table "user_token".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $token
 * @property string $label
 * @property string $mac_address
 * @property string $expire
 * @property string $created_at
 *
 * @property User $user
 */
class UserToken extends \common\models\UserToken
{

    public function extraFields()
    {
        $fields = parent::fields();
        $fields[] = 'user';
        return $fields;
    }

}
