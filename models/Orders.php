<?php

namespace app\models;

use app\models\custom\AuxData;
use yii;
use yii\db\ActiveRecord;

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
 * @property string $comment
 *
 * @property Materials $materials
 */
class Orders extends ActiveRecord
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
            [['status'], 'integer'],
            [['qty'], 'number'],
            [['order_date', 'updated', 'materials_id'], 'safe'],
            [['person'], 'string', 'max' => 64],
            [['docref'], 'string', 'max' => 128],
            [['comment'], 'string'],
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
            'updated' => Yii::t('app', 'Updated'),
            'comment' => Yii::t('app', 'Comments'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterials()
    {
        return $this->hasOne(Materials::className(), ['id' => 'materials_id']);
    }

    public static function getLabels()
    {
        return self::attributeLabels();
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)){
            if ($this->status > 1){
                $this->person = Yii::$app->user->identity->surname . ' ' . Yii::$app->user->identity->name;
            }
            return true;
        }else{
            return false;
        }
    }
}
