<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "usertoken".
 *
 * @property string $id
 * @property string $user_id
 * @property string $token
 * @property string $label
 * @property string $mac_address
 * @property string $expire
 * @property string $created_at
 *
 * @property User $user
 */
class Usertoken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usertoken';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['expire', 'created_at'], 'safe'],
            [['token', 'mac_address'], 'string', 'max' => 32],
            [['label'], 'string', 'max' => 10],
            [['token'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'token' => 'Token',
            'label' => 'Label',
            'mac_address' => 'Mac Address',
            'expire' => 'Expire',
            'created_at' => 'Created At',
        ];
    }

    public function getIsActive()
    {
        $current = time();
        $expire = strtotime($this->expire);
        if ($expire > $current)
            return true;
        else
            return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->refresh();
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
