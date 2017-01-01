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
 * @property mixed $locations
 * @property mixed $stocks
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
            [['qty', 'minqty', 'name'], 'required'],
            [['name'], 'string', 'max' => 128],
            [['unit', 'gruppa'], 'string', 'max' => 3],
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

    public function afterSave($insert, $changedAttributes)
    {
        $storagePath = Yii::getAlias('@app/web/photos/');
        if ($this->file = UploadedFile::getInstance($this, 'file')){
            return $this->file->saveAs($storagePath . $this->ref . '.' . $this->file->extension);
        }else{
            return true;
        }
    }
    
    public function getMovements()
    {
        return $this->hasMany(Movements::className(), ['materials_id' => 'id']);
    }

    public function getLocations()
    {
        return $this->hasMany(Locations::className(), ['materials_id' => 'id']);
    }

    public function getStocks()
    {
        return $this->hasMany(Stocks::className(), ['id' => 'stocks_id'])
            ->via('locations');
    }

    /**
     * @param integer $stocks_id
     * @return mixed
     */
    
    public function getQuantities()
    {
        $temp_array = array_column ($this->locations, 'qty', 'stocks_id');
        $this->setAttribute('qty', array_sum($temp_array));
        $this->save();
        return $temp_array;
    }
}
