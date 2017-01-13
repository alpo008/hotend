<?php

namespace app\models;

use yii;
use yii\db\ActiveRecord;

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
class User extends ActiveRecord
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

    /**
     * Before save event handler
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if(!empty($this->password))
            {
                $this->password = $this->passWithSalt($this->password, 'tuz7773tuz');
                $this->created = date('Y-m-d');
            }
            else
            {
                unset($this->password);
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * Finds user by email
     *
     * @param  string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Validates password
     *
     * @param  string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword ($password)
    {

        return $this->password === $this->passWithSalt($password, 'Dald7773fEo');
    }

    /**
     * @return bool
     * @internal param IdentityInterface $identity
     * @internal param int $duration
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser());
        }
        return false;
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = $this->findByEmail($this->email);
        }

        return $this->_user;
    }

    /**
     * Returns pass with salt
     * @param $password
     * @param $salt
     * @return string
     */
    public function passWithSalt($password, $salt)
    {
        return hash('sha512', $password . $salt);
    }

    /**
     * Calls ActiveQuery child
     * @return UsersQuery
     */
    public static function find()
    {
        return new UsersQuery(get_called_class());
    }


}
