<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "missing".
 *
 * @property string $id
 * @property string $resident_id
 * @property string $reported_by
 * @property string $remark
 * @property string $closure
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Location[] $locations
 * @property LocationHistory[] $locationHistories
 * @property Resident $resident
 * @property Caregiver $reportedBy
 */
class Missing extends \yii\db\ActiveRecord
{
    const STATUS_OPEN = 1;
    const STATUS_CLOSED = 0;

    public static function GetStatusArray()
    {
        return [
            Missing::STATUS_OPEN => 'Open',
            Missing::STATUS_CLOSED => 'Closed',
        ];
    }

    public function getStatusLabel()
    {
        return Missing::GetStatusArray()[$this->status];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'missing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['resident_id', 'reported_by'], 'required'],
            [['resident_id', 'reported_by', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['remark', 'closure'], 'string', 'max' => 500],
            [['resident_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resident::className(), 'targetAttribute' => ['resident_id' => 'id']],
            [['reported_by'], 'exist', 'skipOnError' => true, 'targetClass' => Caregiver::className(), 'targetAttribute' => ['reported_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resident_id' => 'Resident ID',
            'reported_by' => 'Reported By',
            'remark' => 'Remark',
            'closure' => 'Closure',
            'status' => 'Status',
            'created_at' => 'Reported At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Location::className(), ['missing_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationHistories()
    {
        return $this->hasMany(LocationHistory::className(), ['missing_id' => 'id']);
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
    public function getReportedBy()
    {
        return $this->hasOne(Caregiver::className(), ['id' => 'reported_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReportedByRelative()
    {
        return $this->hasOne(Relative::className(), ['id' => 'relative_id'])->viaTable('caregiver', ['id' => 'reported_by']);
    }

}
