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
            return $this->writeCsv($filename, $data);
        }elseif ($data['ext'] === 'xls'){
            return $this->writeXls($filename, $data);
        }else{
            return false;
        }
    }

    /**
     * @param string $name
     * @param array $data
     * @return bool
     */
    public function writeCsv($name, $data){
        if (!!$data['content']){
        $link = fopen($name, 'a+');
        $result = true;
            if (!!$data['labels']){
                $result = fputcsv($link, $data['labels'], ',');
            }
        foreach ($data['content'] as $line) {
            $result = fputcsv($link, $line, ',');
        }
        fclose($link);
        return $result;
        }else{
            return false;
        }
    }

    /**
     * @param array $data
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
            $tbl_body = $this->tableBody($data['labels'], $data['content']);
            fwrite($link, $tbl_body);
            fwrite($link, $htmlend);
            fclose($link);
        return $result;



    }

    /**
     * @param array $labels
     * @param array $content
     * @return string $body
     */
    protected function tableBody ($labels = NULL, $content){
        $body = '<table border="1" align="left">';
        if (!!$labels){
            $body .= '<tr>';
            foreach ($labels as $label){
                $body .= '<th>' . $label . '</th>';
            }
        }
        $body .= '</tr>';
        if (!!$content){
            foreach ($content as $line) {
                $body .= '<tr>';
                foreach ($line as $cell){
                    $body .= '<td>' . $cell . '</td>';
                }
                $body .= '</tr>';
            }
            $body .= '</table>';
        }
        return $body;
    }
}