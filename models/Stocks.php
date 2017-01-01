<?php

namespace app\models;

use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "stocks".
 *
 * @property integer $id
 * @property string $placename
 * @property string $description
 * @property mixed $materials
 */
class Stocks extends ActiveRecord
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
            [['placename'], 'unique'],
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

    public function getLocations()
    {
        return $this->hasMany(Locations::className(), ['stocks_id' => 'id']);
    }

    public function getMaterials()
    {
        return $this->hasMany(Materials::className(), ['id' => 'materials_id'])
            ->via('locations');
    }
}
