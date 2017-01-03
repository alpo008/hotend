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
 * @property string $longname
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
     * @return array
     */

    public function attributes()
    {
        return array_merge(parent::attributes(), ['longname']);
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['materials_id', 'from_to', 'person_in_charge', 'person_receiver', 'qty' ], 'required'],
            [['direction', 'stocks_id'], 'integer'],
            [['qty'], 'number'],
            [['transaction_date', 'materials_id'], 'safe'],
            [['from_to', 'person_in_charge', 'person_receiver'], 'string', 'max' => 64],
            [['docref'], 'string', 'max' => 128],
            [['longname'], 'string', 'min' => 16],
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
            'longname' => Yii::t('app', 'Long Name'),
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {
            $location = $this->getLocation();
            if(!count($location)){
                $location = new Locations();
                $location->materials_id = (int) $this->materials_id;
                $location->stocks_id = $this->stocks_id;
                $location->qty = 0;

            }

                if (!!$this->direction){
                    $quantity = (int) $location->qty + (int) $this->qty;
                }else{
                    $quantity = (int) $location->qty - (int) $this->qty;
                }

            if ($quantity == 0){
                return $location->delete();
            }elseif ($quantity < 0){
                    $this->addError('qty', Yii::t('app', 'The stock rest is too small for this movement'));
                    return false;
                }else{
                    $location->setAttribute('qty', $quantity);
                    return $location->save();
                }


        } else {
            return false;
        }
    }


    public function getMaterials()
    {
        return $this->hasOne(Materials::className(), ['id' => 'materials_id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getStocks()
    {
        return $this->hasOne(Stocks::className(), ['id' => 'stocks_id']);
    }

    /**
     * @return array|null|ActiveRecord
     */
    public function getLocation()
    {
        return Locations::find()->where(['stocks_id' => $this->stocks_id, 'materials_id' => $this->materials_id])->one();
    }
}
