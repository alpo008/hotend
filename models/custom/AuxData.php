<?php
/**
 * Created by PhpStorm.
 * User: alpo
 * Date: 28.12.16
 * Time: 14:56
 */

namespace app\models\custom;


use app\models\Materials;
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
            ->select (['code' => 'id', 'value' => "concat (id, '; ', ref, '; ',name)"])
            ->asArray()
            ->all();
        return array_column($temp, 'value', 'code');
    }


}