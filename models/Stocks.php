<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stocks".
 *
 * @property integer $id
 * @property string $placename
 * @property string $description
 */
class Stocks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stocks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['placename', 'description'], 'required'],
            [['description'], 'string'],
            [['placename'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'placename' => Yii::t('app', 'Placename'),
            'description' => Yii::t('app', 'Description'),
        ];
    }
}
