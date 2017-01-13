<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string $created
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'email', 'password', 'role'], 'required'],
            [['created'], 'safe'],
            [['name'], 'string', 'max' => 25],
            [['surname'], 'string', 'max' => 35],
            [['email'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 256],
            [['role'], 'string', 'max' => 30],
            [['email'], 'unique'],
            [['password'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'surname' => Yii::t('app', 'Surname'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'role' => Yii::t('app', 'Role'),
            'created' => Yii::t('app', 'Created'),
        ];
    }
}
