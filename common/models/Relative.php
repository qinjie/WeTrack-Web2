<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "relative".
 *
 * @property string $id
 * @property string $fullname
 * @property string $nric
 * @property string $phone
 * @property string $email
 * @property string $user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Caregiver[] $caregivers
 * @property Resident[] $residents
 * @property User $user
 */
class Relative extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'relative';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fullname'], 'required'],
            [['user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['fullname'], 'string', 'max' => 200],
            [['nric', 'phone'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 100],
            // the email attribute must be unique in table
            [['email'], 'unique'],
            // the email attribute should be a valid email address
            ['email', 'email'],
            [['user_id'], 'unique'],
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
            'fullname' => 'Fullname',
            'nric' => 'Nric',
            'phone' => 'Phone',
            'email' => 'Email',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaregivers()
    {
        return $this->hasMany(Caregiver::className(), ['relative_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResidents()
    {
        return $this->hasMany(Resident::className(), ['id' => 'resident_id'])->viaTable('caregiver', ['relative_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
