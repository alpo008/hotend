<?php

namespace app\models;

use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "movements".
 *
 * @property integer $id
 * @property integer $materials_id
 * @property integer $direction
 * @property string $qty
 * @property string $from_to
 * @property string $transaction_date
 * @property integer $stocks_id
 * @property string $person_in_charge
 * @property string $person_receiver
 * @property string $docref
 */
class Movements extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'movements';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['materials_id', 'from_to', 'person_in_charge', 'person_receiver'], 'required'],
            [['materials_id', 'direction', 'stocks_id'], 'integer'],
            [['qty'], 'number'],
            [['transaction_date'], 'safe'],
            [['from_to', 'person_in_charge', 'person_receiver'], 'string', 'max' => 64],
            [['docref'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'materials_id' => Yii::t('app', 'Material'),
            'direction' => Yii::t('app', 'Stock Direction'),
            'qty' => Yii::t('app', 'Qty'),
            'from_to' => Yii::t('app', 'From To'),
            'transaction_date' => Yii::t('app', 'Transaction Date'),
            'stocks_id' => Yii::t('app', 'Stocks ID'),
            'person_in_charge' => Yii::t('app', 'Person In Charge'),
            'person_receiver' => Yii::t('app', 'Person Receiver'),
            'docref' => Yii::t('app', 'Docref'),
        ];
    }

    public function getMaterials()
    {
        return $this->hasOne(Materials::className(), ['id' => 'materials_id']);
    }
}
