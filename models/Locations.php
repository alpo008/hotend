<?php

namespace app\models;

use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "locations".
 *
 * @property integer $materials_id
 * @property integer $stocks_id
 * @property string $qty
 */
class Locations extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'locations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['materials_id', 'stocks_id'], 'integer'],
            [['qty'], 'number', 'min' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'materials_id' => Yii::t('app', 'Materials ID'),
            'stocks_id' => Yii::t('app', 'Stocks ID'),
            'qty' => Yii::t('app', 'Qty'),
        ];
    }

    public function getMaterials()
    {
        return $this->hasOne(Materials::className(), ['id' => 'materials_id']);
    }
    
    public function getStocks()
    {
        return $this->hasOne(Stocks::className(), ['id' => 'stocks_id']);
    }
}

