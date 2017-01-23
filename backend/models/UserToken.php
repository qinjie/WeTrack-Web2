<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "usertoken".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $token
 * @property string $label
 * @property string $ip_address
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
            [['token', 'ip_address'], 'string', 'max' => 32],
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
            'ip_address' => 'Ip Address',
            'expire' => 'Expire',
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
