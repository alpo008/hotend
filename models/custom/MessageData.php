<?php
/**
 * Created by PhpStorm.
 * User: alpo
 * Date: 08.02.17
 * Time: 12:18
 */

namespace app\models\custom;


use app\models\Materials;
use yii;
use yii\base\Model;

class MessageData extends  Model
{
    public  static $instance = NULL;
    
    public static function getInstance()
    {
        if (self::$instance === NULL)
        {
            self::$instance = new self;
        }
        return self::$instance;
    }
    
    public function prepareDownloads()
    {
        $urgents = AuxData::getUrgents();
        $output_data = TempFile::getInstance();
        $download_data = array();
        $mail_labels = AuxData::getUrgentsLabels(Materials::getLabels());
        $mail_list = NULL;

        if(!!$urgents){
            $mail_list = AuxData::updateUrgents($urgents);
            $output_data->saveTemp([
                'name' => 'temp',
                'ext' => 'csv',
                'content' => $mail_list,
                'labels' => $mail_labels,
            ]);

            $urgents_labels = AuxData::getLabels([
                ['materials',
                    ['id', 'ref', 'name', 'qty', 'minqty', 'unit', 'type', 'gruppa']]
            ]);
            $output_data->saveTemp([
                'name' => 'urgents',
                'ext' => 'xls',
                'content' => $urgents,
                'labels' => $urgents_labels,
            ]);
            $download_data[Yii::t('app', 'Urgent orders')] = 'urgents.xls';
            if(!!$mail_list){
                $output_data->saveTemp([
                    'name' => 'emails',
                    'ext' => 'xls',
                    'content' => $mail_list,
                    'labels' => $mail_labels,
                ]);
                $download_data[Yii::t('app', 'Urgent messages')] = 'emails.xls';
            }
        }
        $materials_labels =  AuxData::getLabels([
            ['materials',['id', 'ref', 'name', 'qty', 'minqty', 'unit', 'type', 'gruppa']],
            ['stocks', ['placename']]
        ]);
        $output_data->saveTemp([
            'name' => 'materials',
            'ext' => 'xls',
            'content' => AuxData::getFullTable(),
            'labels' => $materials_labels,
        ]);
        $download_data[Yii::t('app', 'Materials')] = 'materials.xls';

        $orders_labels = AuxData::getLabels([
            ['orders',['order_date' , 'docref']],
            ['materials', ['ref', 'name']],
            ['orders',['qty', 'status']],
        ]);
        $output_data->saveTemp([
            'name' => 'orders',
            'ext' => 'xls',
            'content' => AuxData::getOrders(),
            'labels' => $orders_labels,
        ]);
        $download_data[Yii::t('app', 'Orders')] = 'orders.xls';

        $orders_labels = AuxData::getLabels([
            ['stocks',['placename' , 'description']],
            ['materials', ['ref', 'name', 'qty']],
        ]);
        $output_data->saveTemp([
            'name' => 'stock',
            'ext' => 'xls',
            'content' => AuxData::getStockTable(),
            'labels' => $orders_labels,
        ]);
        $download_data[Yii::t('app', 'Stocks')] = 'stock.xls';

        $message = (!$mail_list) ? true : false;

        $lists['statuses'] = AuxData::getOrderStatus();
        $lists['downloads'] = $download_data;
        
        return array ('lists' => $lists, 'message' => $message);
    }
}