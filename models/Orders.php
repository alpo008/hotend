<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property integer $materials_id
 * @property string $qty
 * @property string $order_date
 * @property string $status
 * @property string $person
 * @property string $docref
 *
 * @property Materials $materials
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['materials_id', 'order_date'], 'required'],
            [['materials_id'], 'integer'],
            [['qty'], 'number'],
            [['order_date'], 'safe'],
            [['status'], 'string', 'max' => 32],
            [['person'], 'string', 'max' => 64],
            [['docref'], 'string', 'max' => 128],
            [['materials_id'], 'exist', 'skipOnError' => true, 'targetClass' => Materials::className(), 'targetAttribute' => ['materials_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'materials_id' => Yii::t('app', 'Materials ID'),
            'qty' => Yii::t('app', 'Qty'),
            'order_date' => Yii::t('app', 'Order Date'),
            'status' => Yii::t('app', 'Status'),
            'person' => Yii::t('app', 'Applicant'),
            'docref' => Yii::t('app', 'Order ref'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterials()
    {
        return $this->hasOne(Materials::className(), ['id' => 'materials_id']);
    }
}
