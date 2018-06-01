<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "caregiver".
 *
 * @property string $id
 * @property string $relative_id
 * @property string $resident_id
 * @property string $relation
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Resident $resident
 * @property Relative $relative
 */
class Caregiver extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'caregiver';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['relative_id', 'resident_id', 'relation'], 'required'],
            [['relative_id', 'resident_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['relation'], 'string', 'max' => 50],
            [['relative_id', 'resident_id'], 'unique', 'targetAttribute' => ['relative_id', 'resident_id'], 'message' => 'The combination of Relative ID and Resident ID has already been taken.'],
            [['resident_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resident::className(), 'targetAttribute' => ['resident_id' => 'id']],
            [['relative_id'], 'exist', 'skipOnError' => true, 'targetClass' => Relative::className(), 'targetAttribute' => ['relative_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'relative_id' => 'Relative ID',
            'resident_id' => 'Resident ID',
            'relation' => 'Relation',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
    public function getRelative()
    {
        return $this->hasOne(Relative::className(), ['id' => 'relative_id']);
    }
}
