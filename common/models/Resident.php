<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "resident".
 *
 * @property string $id
 * @property string $fullname
 * @property string $dob
 * @property string $nric
 * @property string $image_path
 * @property string $thumbnail_path
 * @property integer $hide_photo
 * @property integer $status
 * @property string $remark
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Beacon[] $beacons
 * @property Caregiver[] $caregivers
 * @property Location[] $locations
 * @property LocationHistory[] $locationHistories
 * @property Missing[] $missings
 */
class Resident extends \yii\db\ActiveRecord
{
    const STATUS_AVAILABLE = 0;
    const STATUS_MISSING = 1;

    public static function GetStatusArray()
    {
        return [
            Resident::STATUS_AVAILABLE => 'Available',
            Resident::STATUS_MISSING => 'Missing'
        ];
    }

    const LABEL_YES = 1;
    const LABEL_NO = 0;

    public static function GetYesNoArray()
    {
        return [
            Resident::LABEL_NO => 'No',
            Resident::LABEL_YES => 'Yes'
        ];
    }

    public function getReportedDate()
    {
        return \Yii::$app->formatter->asDatetime($this->created_at, "php:d-m-Y H:i:s");
    }

    public function getHidePhotoLabel()
    {
        return Resident::GetYesNoArray()[$this->hide_photo];
    }

    public function getStatusLabel()
    {
        return Resident::GetStatusArray()[$this->status];
    }

    /**
     * @inheritdoc
     */
    public $file;

    public static function tableName()
    {
        return 'resident';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fullname', 'dob'], 'required'],
            [['dob', 'created_at', 'updated_at'], 'safe'],
            [['hide_photo', 'status'], 'integer'],
            [['fullname', 'image_path', 'thumbnail_path'], 'string', 'max' => 200],
            [['nric'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 500],
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
            'dob' => 'Dob',
            'nric' => 'Nric',
            'file' => 'Image',
            'image_path' => 'Image Path',
            'thumbnail_path' => 'Thumbnail Path',
            'hide_photo' => 'Hide Photo',
            'status' => 'Status',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getLatestLocation()
    {
        return $this->hasMany(Location::className(), ['beacon_id' => 'id'])->via('beacons')->orderBy(['created_at' => SORT_DESC])->limit(1);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeacons()
    {
        return $this->hasMany(Beacon::className(), ['resident_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaregivers()
    {
        return $this->hasMany(Caregiver::className(), ['resident_id' => 'id']);
    }

    public function getRelatives()
    {
        return $this->hasMany(Relative::className(), ['id' => 'relative_id'])->viaTable('caregiver', ['resident_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Location::className(), ['resident_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationHistories($limit = 5)
    {
        return $this->hasMany(LocationHistory::className(), ['beacon_id' => 'id'])->via('beacons')->orderBy(['id' => SORT_DESC])->limit($limit);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMissings()
    {
        return $this->hasMany(Missing::className(), ['resident_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActiveMissing()
    {
        return $this->hasOne(Missing::className(), ['resident_id' => 'id', 'status' => Missing::STATUS_OPEN]);
    }

}
