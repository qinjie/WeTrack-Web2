<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_resident".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $resident_id
 * @property string $relation
 * @property string $created_at
 *
 * @property Resident $resident
 * @property User $user
 */
class UserResident extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_resident';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'resident_id', 'relation'], 'required'],
            [['user_id', 'resident_id'], 'integer'],
            [['relation'], 'string'],
            [['created_at'], 'safe'],
            [['resident_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resident::className(), 'targetAttribute' => ['resident_id' => 'id']],
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
            'resident_id' => 'Resident ID',
            'relation' => 'Relation',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResident()
    {
        return $this->hasOne(Resident::className(), ['id' => 'resident_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
