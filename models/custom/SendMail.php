<?php
/**
 * Created by PhpStorm.
 * User: alpo
 * Date: 12.01.17
 * Time: 16:16
 */

namespace app\models\custom;


use yii;
use yii\base\Model;

class SendMail extends Model
{

    public static function sendNotification($fileName)
    {
        $source = TempFile::getInstance();
        $data = $source->readCsv([
            'name' => 'temp',
            'ext' => 'csv',
        ]);

        $message_body = "<table>";

        foreach ($data as $line) {
            $message_body .= '<tr>';
            foreach ($line as $cell){
                $message_body .= '<td>' . $cell . '</td>';
            }
            $message_body .='</tr>';
        }
        $message_body .= "</table>";

        Yii::$app->mailer->compose('@app/mail/layouts/html.php', ['content' => $message_body])
        ->setTo('test@test.ru')
        ->setFrom('stock@test.ru')
        ->setSubject('Test message')
        //->attach($fileName)
        ->send();
        return true;
    }
}