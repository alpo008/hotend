<?php

namespace app\models;

use Yii;

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
 * @property integer $gruppa
 */
class Materials extends \yii\db\ActiveRecord
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
            [['ref', 'gruppa'], 'integer'],
            [['qty', 'minqty'], 'number'],
            [['name'], 'string', 'max' => 128],
            [['unit'], 'string', 'max' => 3],
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
            'ref' => Yii::t('app', 'Ref'),
            'name' => Yii::t('app', 'Name'),
            'qty' => Yii::t('app', 'Qty'),
            'minqty' => Yii::t('app', 'Minqty'),
            'unit' => Yii::t('app', 'Unit'),
            'type' => Yii::t('app', 'Type'),
            'gruppa' => Yii::t('app', 'Gruppa'),
        ];
    }
}
