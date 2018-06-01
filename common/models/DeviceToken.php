<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "device_token".
 *
 * @property string $id
 * @property string $user_id
 * @property string $token
 * @property string $created_at
 *
 * @property User $user
 */
class DeviceToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'device_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['token'], 'string', 'max' => 32],
            [['user_id', 'token'], 'unique', 'targetAttribute' => ['user_id', 'token'], 'message' => 'The combination of User ID and Token has already been taken.'],
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
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
