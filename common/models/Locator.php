<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "locator".
 *
 * @property string $id
 * @property string $serial
 * @property string $label
 * @property string $remark
 * @property string $address
 * @property string $postal
 * @property string $longitude
 * @property string $latitude
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Location[] $locations
 * @property LocationHistory[] $locationHistories
 */
class Locator extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'locator';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['serial', 'label'], 'required'],
            [['longitude', 'latitude'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['serial'], 'string', 'max' => 50],
            [['label'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 500],
            [['address'], 'string', 'max' => 200],
            [['postal'], 'string', 'max' => 10],
            [['serial'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serial' => 'Serial',
            'label' => 'Label',
            'remark' => 'Remark',
            'address' => 'Address',
            'postal' => 'Postal',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Location::className(), ['locator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationHistories()
    {
        return $this->hasMany(LocationHistory::className(), ['locator_id' => 'id']);
    }
}
