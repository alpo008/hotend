<?php

namespace app\models;

use yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

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
class User extends ActiveRecord implements IdentityInterface
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
                $this->password = $this->passWithSalt($this->password, 'Dald7773fEo');
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
     * @return UserQuery
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return boolean whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    /**
     * @param string$user
     * @param string $contr
     * @param string $act
     * @return bool
     */
    public static function checkRights($user, $contr, $act)
    {
        return (!!$user && !!$contr && !!$act) ? self::getRights()[$user][$contr][$act] : false;
    }

    /**
     * @return array
     */
    public static function getDropdown()
    {
        return array (
            'ADMIN' => 'ADMIN',
            'ENGINEER' => 'ENGINEER',
            'OPERATOR' => 'OPERATOR',
         );
    }

    /**
     * @return array
     */
    public static function getRights()
    {
        return array(
            'ADMIN' => array(
                'materials' => array(
                    'index' =>true,
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true,
                ),
                'movements' => array(
                    'index' =>true,
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true,
                ),
                'orders' => array(
                    'index' =>true,
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true,
                ),
                'stocks' => array(
                    'index' =>true,
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true,
                ),
                'user' => array(
                    'index' =>true,
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true,
                )
            ),

            'ENGINEER' => array(
                'materials' => array(
                    'index' =>true,
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true,
                ),
                'movements' => array(
                    'index' =>true,
                    'view' => true,
                    'create' => true,
                    'update' => false,
                    'delete' => false,
                ),
                'orders' => array(
                    'index' =>true,
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => false,
                ),
                'stocks' => array(
                    'index' =>true,
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true,
                ),
                'user' => array(
                    'index' =>true,
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true,
                ),
            ),

            'OPERATOR' => array(
                'materials' => array(
                    'index' =>true,
                    'view' => true,
                    'create' => false,
                    'update' => false,
                    'delete' => false,
                ),
                'movements' => array(
                    'index' =>true,
                    'view' => true,
                    'create' => true,
                    'update' => false,
                    'delete' => false,
                ),
                'orders' => array(
                    'index' =>false,
                    'view' => false,
                    'create' => false,
                    'update' => false,
                    'delete' => false,
                ),
                'stocks' => array(
                    'index' =>true,
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => false,
                ),
                'user' => array(
                    'index' =>false,
                    'view' => false,
                    'create' => false,
                    'update' => false,
                    'delete' => false,
                ),
            ),
        );
    }
}
