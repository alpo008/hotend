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
        Yii::$app->mailer->compose()
        ->setTo('test@test.ru')
        ->setFrom('stock@test.ru')
        ->setSubject('Spare parts status')
        ->setTextBody('Test notification')
        ->attach($fileName)
        ->send();

        return true;
    }
}