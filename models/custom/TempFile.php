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
        $this->storagePath = Yii::getAlias('@app/temp/');
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
     * @return string
     */
    public function getStoragePath()
    {
        return $this->storagePath;
    }

    /**
     * @param array $data
     * @return bool $result
     */

    public  function saveTemp ($data)
    {
        $filename = $this->getStoragePath() . $data['name'] . '.' . $data['ext'];
        if ($data['ext'] === 'csv'){
            return $this->writeCsv($filename, $data['content']);
        }elseif ($data['ext'] === 'xls'){
            return $this->writeXls($filename, $data['content']);
        }else{
            return false;
        }
    }

    public function writeCsv($name, $data){
        $link = fopen($name, 'a+');
        $result = true;
        foreach ($data as $line) {
            $result = fputcsv($link, $line, ',');
        }
        fclose($link);
        return $result;
    }

    /**
     * @param $data
     * @return array|bool
     */

    public function readCsv($data)
    {
        $line = 1;
        $result = array();
        if ($link = fopen($this->storagePath . $data['name'] . '.' . $data['ext'], "r")) {
            while ($temp = fgetcsv($link, 1000, ",")) {
                if ($temp){
                    $result[] = $temp;
                }
                $line++;
            }
            fclose($link);
            return $result;
        }else{
            return false;
        }
    }

    /**
     * @param string $name
     * @param array $data
     * @return bool
     */
    public  function writeXls ($name, $data)
    {
        $htmlbegin =
            '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
        <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8" />
            <meta name="ufaelectron" content="table" />
            <title>Downloaded</title>
        </head>
        <body>';
        $htmlend = '</body></html>';

            $result = true;
            $link = fopen($name, 'w+');
            fwrite($link, $htmlbegin);
            fwrite($link, '<table border="1" align="left">');
            foreach ($data as $line) {
                $result = fwrite($link, '<tr>');
                foreach ($line as $cell){
                    $result = fwrite($link, '<td>' . $cell . '</td>');
                }
                fwrite($link, '</tr>');
            }
            fwrite($link, '</table>');
            fwrite($link, $htmlend);
            fclose($link);
        return $result;
    }
}