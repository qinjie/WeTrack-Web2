<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "usertoken".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $token
 * @property string $label
 * @property string $mac_address
 * @property string $expire
 * @property string $created_at
 * @property string $is_active
 *
 * @property User $user
 */
class UserToken extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usertoken';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                // Modify only created not updated attribute
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => null,
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
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
            [['token'], 'unique']
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
            'mac_address' => 'Ip Address',
            'expire' => 'Expire',
            'created_at' => 'Created',
            'is_active' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->refresh();
        parent::afterSave($insert, $changedAttributes);
    }

    public function getIsActive()
    {
        // check whether token has expired.
        $current = time();
        $expire = strtotime($this->expire);
        if ($expire > $current)
            return true;
        else
            return false;
    }
}
