<?php

namespace app\models;

use yii;
use yii\db\ActiveRecord;

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
 */
class Materials extends ActiveRecord
{
    /**
     * @inheritdoc
     */
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
            [['qty', 'minqty'], 'number'],
            [['name'], 'string', 'max' => 128],
            [['unit', 'gruppa'], 'string', 'max' => 3],
            [['type'], 'string', 'max' => 8],
            [['ref'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ref' => Yii::t('app', 'Material'),
            'name' => Yii::t('app', 'Material description'),
            'qty' => Yii::t('app', 'Stock qty'),
            'minqty' => Yii::t('app', 'Minimal qty'),
            'unit' => Yii::t('app', 'Unit'),
            'type' => Yii::t('app', 'Type'),
            'gruppa' => Yii::t('app', 'Group'),
            'stock_rest' => Yii::t('app', 'Stock Rest'),
            'stock_min' => Yii::t('app', 'Min Stock'),
        ];
    }
    
    public function getMovements()
    {
        return $this->hasMany(Movements::className(), ['materials_id' => 'ref']);
    }
}
