<?php
/**
 * Created by PhpStorm.
 * User: alpo
 * Date: 28.12.16
 * Time: 14:56
 */

namespace app\models\custom;


use app\models\Locations;
use app\models\Materials;
use app\models\Movements;
use app\models\Orders;
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
    
    public static function getLocations(){
        return Locations::find()
            ->asArray()
            ->all();
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

    /**
     * @return array|yii\db\ActiveRecord[]
     */
    public static function getUrgents ()
    {
        return Materials::find()
            ->where(['>=', '([[materials.minqty]] - [[materials.qty]])', 0])
            ->andWhere(['>', 'materials.minqty', 0])->joinWith(['orders'])
            ->all();
    }
    
    public  static  function getUrgentsLabels($all_labels){
        return  [
            $all_labels['ref'],
            $all_labels['name'],
            Yii::t('app', 'Qty'),
            $all_labels['unit'],
            $all_labels['type'],
            $all_labels['gruppa'],
            Yii::t('app', 'Status'),
        ];
    }

    /**
     * @param array $urgents
     * @return array $updated_list
     */
    public static function updateUrgents ($urgents)
    {
        $two_weeks_ago = date ('Y-m-d', mktime(0, 0, 0, date("m"),   date("d") -14,   date("Y")));
        $updated_list = array();
        foreach ($urgents as $urgent){
            if (!$urgent['orders']){
                $updated_list[] = self::createNewOrder($urgent);
            }else{

                $orders_map = array_column ($urgent['orders'], 'status', 'id');
                if (min($orders_map) <= 4){
                    $min_key = array_search(min($orders_map), $orders_map);
                    $order_to_update = Orders::findOne(['id' => $min_key]);
                    if ($order_to_update->attributes['updated'] < $two_weeks_ago){
                        $updated_list[] = self::updateExistingOrder($order_to_update);
                    }
                }elseif ((max($orders_map) >= 5) && (min($orders_map) >= 5)){
                    $updated_list[] = self::createNewOrder($urgent);
                }
            }
        }
        return $updated_list;

    }

    /**
     * @param object $item
     * @return array $update_list
     */
    public static function createNewOrder($item){
        $new_order = new Orders();
        $new_order->materials_id = $item->id;
        $new_order->setAttributes([
            'materials_id' => $item->id,
            'qty' => $item->minqty,
            'order_date' => date ('Y-m-d'),
            'status' => 0,
            'person' => Yii::t('app', 'Generated by system'),
            'docref' => NULL,
            'updated' => date ('Y-m-d'),
        ]);
        $update_list = [
            $new_order->materials->ref,
            $new_order->materials->name,
            $new_order->materials->qty,
            $new_order->materials->unit,
            $new_order->materials->type,
            $new_order->materials->gruppa,
            self::getOrderStatus()[$new_order->status],
            ];
            $new_order->save();
            return $update_list;
    }

    /**
     * @param object $item
     * @return array $update_list
     */
    public  static  function updateExistingOrder ($item)
    {
        $item->setAttributes([
            'updated' => date ('Y-m-d'),
            'status' => ($item->status > 1)? $item->status : 1,
        ]);
        $update_list = [
            $item->materials->ref,
            $item->materials->name,
            $item->materials->qty,
            $item->materials->unit,
            $item->materials->type,
            $item->materials->gruppa,
            self::getOrderStatus()[$item->status],
        ];
        $item->save();
        return $update_list;
    }

    /**
     * @return array $result
     */
    public static  function getFullTable()
    {
        $request = Materials::find()->joinWith(['stocks'])->asArray()->all();

        $result = array();
        foreach ($request as $entry){
            $line = array();
            foreach ($entry as $k => $v){
                if (!is_array($v)){
                    $line[$k] = $v;
                }else{
                    switch ($k){
                        case 'stocks' :
                            $line['placename'] = implode( array_column($v, 'placename'), '; ');
                        break;
                    }
                }
            }
            $result[] = $line;
        }
        return $result;
    }

    /**
     * @param array $attrs
     * @return array $labels
     */
    public static function getLabels($attrs){
        $labels = array();
        foreach ($attrs as $table_data) {
            switch ($table_data[0]) {
                case 'materials':
                    $labels_array = Materials::getLabels();
                    break;
                case 'orders':
                    $labels_array = Orders::getLabels();
                    break;
                case 'movements':
                    $labels_array = Movements::getLabels();
                    break;
                case 'stocks':
                    $labels_array = Stocks::getLabels();
                    break;
            }
            foreach ($table_data[1] as $lbl_data){
              if (!empty($labels_array)) {
                  $labels[] = $labels_array[$lbl_data];
              }
            }
        }
        return $labels;
    }

    /**
     * @return array|yii\db\ActiveRecord[] $orders_array
     */
    public static function  getOrders (){
    $orders_array = Orders::find()
                    ->leftJoin('materials', '`orders`.`materials_id` = `materials`.`id`')
                    ->select(['`orders`.`order_date`', '`orders`.`docref`', '`materials`.`ref`', '`materials`.`name`', '`orders`.`qty`','`orders`.`status`' ])
                    ->orderBy('`orders`.`order_date` DESC')
                    ->asArray()
                    ->all();
        $order_status = self::getOrderStatus();
        array_walk_recursive($orders_array, function (&$v, $k) use ($order_status){
            if ($k == 'status'){
               $v = $order_status[$v];
            }
        });
    return $orders_array;
    }

    /**
     * @return array $stock_table
     */
    public static function getStockTable(){
        $stock_table = array();
        $stock_objects = Stocks::find()
            ->joinWith('materials')
            ->orderBy('stocks.placename')
            ->asArray()
            ->all();
        foreach ($stock_objects as $stock_object){
            $duplicate = true;
            foreach ($stock_object['materials'] as $material){
                $qty = 0;
                foreach ($stock_object['locations'] as $location){
                    if ($location['materials_id'] === $material['id']){
                        $qty .= $location['qty'];
                    }
                }
                $stock_table[] = [
                    'placename' => ($duplicate) ? $stock_object['placename'] : '',
                    'description' => ($duplicate) ? $stock_object['description'] : '',
                    'ref' => $material['ref'],
                    'name' => $material['name'],
                    'qty' => $qty,
                ];
                $duplicate = false;
            }
        }
        return $stock_table;
    }
    
    public static function updateAllQuantitites(){
        $materials_data = Materials::find()->joinWith('locations')->all();

        foreach ($materials_data as $materials_object){
            $mat_qties = array_column($materials_object->locations, 'qty');
            if ($materials_object->qty = array_sum($mat_qties)){
                $materials_object->save();
            }
        }
    }
}