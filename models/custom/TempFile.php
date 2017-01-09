<?php
/**
 * Created by PhpStorm.
 * User: alpo
 * Date: 07.01.17
 * Time: 17:42
 */

namespace app\models\custom;

use yii;


class TempFile
{
    protected static $instance = NULL;
    
    protected $storagePath;

    protected function __construct(){
        $this->storagePath = Yii::getAlias('@app/temp/temp.csv');
    }

    /**
     * @return object TempFile|null
     */
    public static function getInstance()
    {
        if (self::$instance === NULL)
        {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * @param array $data
     * @return bool $result
     */
    public function writeCsv($data){
        $link = fopen($this->storagePath, 'a+');
        $result = true;
        foreach ($data as $line) {
            fputcsv($link, $line, ',');
        }
        fclose($link);
        return $result;
    }

    /**
     * @return array|bool
     */
    public function readCsv(){
        $line = 1;
        $data = array();
        if ($link = fopen($this->storagePath, "r")) {
            while ($temp = fgetcsv($link, 1000, ",")) {
                if ($temp){
                    $data[] = $temp;
                }
                $line++;
            }
            fclose($link);
            return $data;
        }else{
            return false;
        }
    }
}