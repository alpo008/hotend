<?php
/**
 * Created by PhpStorm.
 * User: alpo
 * Date: 28.12.16
 * Time: 14:56
 */

namespace app\models\custom;


use app\models\Materials;
use app\models\Stocks;
use yii;
use yii\base\Model;

class AuxData extends Model
{
    public static function getUnits(){
        return array(
            'ШТ' => 'ШТ',
            'М' => 'М',
            'ПАР' => 'ПАР',
        );
    }    
    
    public static function getDirections(){
        return array(
            '0' => 'Расход',
            '1' => 'Приход',
        );
    }    
    public static function getMaterials(){
        $temp = Materials::find()
            ->select (['m_id' => 'id', 'value' => "concat (id, '; ', ref, '; ',name)"])
            ->asArray()
            ->all();
        return array_column($temp, 'value', 'm_id');
    }    
    
    public static function getStocks(){
        $temp = Stocks::find()
            ->select (['s_id' => 'id', 'value' => 'placename'])
            ->asArray()
            ->all();
        return array_column($temp, 'value', 's_id');
    }    
    
    public static function getOrderStatus(){
        return array(
            '0' => Yii::t('app', 'To do'),
            '1' => Yii::t('app', 'Out of date'),
            '2' => Yii::t('app', 'Placed'),
            '3' => Yii::t('app', 'Paid'),
            '4' => Yii::t('app', 'Arrived'),
            '5' => Yii::t('app', 'Completed'),
            '6' => Yii::t('app', 'Canceled'),


        );
    }


}