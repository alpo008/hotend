<?php

namespace app\models;

use yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "materials".
 *
 * @property integer $id
 * @property integer $ref
 * @property string $name
 * @property string $qty
 * @property string $minqty
 * @property string $unit
 * @property string $type
 * @property string $gruppa
 * @property resource $file
 *
 * @property string $photoPath
 * @property Locations[] $locations
 * @property Stocks[] $stocks
 */
class Materials extends ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $file;
    public $fullRef;

    public static function tableName()
    {
        return 'materials';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ref'], 'integer'],
            [['ref', 'qty', 'minqty'], 'number'],
            [['minqty', 'name'], 'required'],
            [['name'], 'string', 'max' => 128],
            [['unit'], 'string', 'max' => 3],
            [['gruppa'], 'string', 'max' => 16],
            [['type'], 'string', 'max' => 8],
            [['ref'], 'unique'],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ref' => Yii::t('app', 'Material ref'),
            'name' => Yii::t('app', 'Material description'),
            'qty' => Yii::t('app', 'Stock qty'),
            'minqty' => Yii::t('app', 'Minimal qty'),
            'unit' => Yii::t('app', 'Unit'),
            'type' => Yii::t('app', 'Type'),
            'gruppa' => Yii::t('app', 'Group'),
            'stock_rest' => Yii::t('app', 'Stock Rest'),
            'stock_min' => Yii::t('app', 'Min Stock'),
            'file' => Yii::t('app', 'Upload image')
        ];
    }

    /**
     * @inheritDoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        $storagePath = Yii::getAlias('@app/web/photos/');
        if ($this->file = UploadedFile::getInstance($this, 'file')){
            return $this->file->saveAs($storagePath . $this->ref . '.' . $this->file->extension);
        }else{
            return true;
        }
    }

    /**
     * @inheritDoc
     */
    public function beforeDelete()
    {
        $result = parent::beforeDelete();

        $movement_data = $this->movements;
        foreach ($movement_data as $movement_obj){
            $result = $movement_obj->delete();
        }
        $location_data = $this->locations;
        foreach ($location_data as $location_obj){
            $result = $location_obj->delete();
        }
        $order_data = $this->orders;
        foreach ($order_data as $order_obj){
            $result = $order_obj->delete();
        }
        return $result;
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getMovements()
    {
        return $this->hasMany(Movements::className(), ['materials_id' => 'id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Locations::className(), ['materials_id' => 'id']);
    }


    /**
     * @return yii\db\ActiveQuery
     */
    public function getStocks()
    {
        return $this->hasMany(Stocks::className(), ['id' => 'stocks_id'])
            ->via('locations');
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['materials_id' => 'id']);
    }

    
    /**
     * This function was modified to avoid using array_column function for php5.6
     * @return array $temp_array
     */
    public function getQuantities()
    {
        $temp_array = array();
        foreach ($this->locations as $loc){
            $temp_array[$loc->stocks_id] = $loc->qty;
        }
        $this->updateQuantity(array_sum($temp_array));
        return $temp_array;
    }

    /**
     * @return string
     */
    public function getPhotoPath()
    {
        $imgfile = '@web/photos/' . $this->ref . '.jpg';
        return file_exists($_SERVER['DOCUMENT_ROOT'] . '/hotend/photos/' . $this->ref . '.jpg') ?
            $imgfile : '@web/photos/_no-image.jpg';
    }

    /**
     * @param string $qty
     */
    public function  updateQuantity($qty='0')
    {
        $this->setAttribute('qty', $qty);
        $this->qty = $qty;
        $this->save();
    }


    /**
     * @return array
     */
    public static function getLabels()
    {
        return self::attributeLabels();
    }
}
