<?php
/**
 * Created by PhpStorm.
 * User: alpo
 * Date: 28.12.16
 * Time: 14:56
 */

namespace app\models\custom;


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


}