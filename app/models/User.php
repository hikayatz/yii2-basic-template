<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;
    public $new_password, $old_password, $repeat_password, $_image;

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i'),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['status'], 'integer'],
            [['username', 'email'], 'trim'],
            [['username', 'email', 'fullname'], 'string', 'max' => 100],
            ['email', 'unique', 'targetClass' => self::className()],
            ['username', 'unique', 'targetClass' => self::className()],

            [['last_login', 'created_at', 'updated_at', 'photo', 'access_token'], 'safe'],
            [['password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['_image'], 'string', 'max' => 255],
            [['_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'gif, png, jpg'],

            [['old_password'], 'validateCurrentPassword', 'on' => 'update'],
            [['old_password', 'new_password', 'repeat_password'], 'string', 'min' => 6, 'on' => 'update'],
            [['repeat_password'], 'compare', 'compareAttribute' => 'new_password'],
            [['old_password', 'new_password', 'repeat_password'], 'required', 'when' => function ($model) {
                return (!empty($model->new_password));
            }, 'whenClient' => "function (attribute, value) {
                return ($('#user-new_password').val().length>0);
            }"],
        ];
    }
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['password'] = ['old_password', 'new_password', 'repeat_password'];
        $scenarios['update'] = ['old_password', 'new_password', 'repeat_password'];
        return $scenarios;
    }
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'status' => self::STATUS_ACTIVE]);

    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);

    }
    /**
     * Finds user by password reset token
     *
     * @param  string      $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->access_token;
    }

    public function generateAuthKey()
    {
        $this->access_token = Yii::$app->security->generateRandomString();
    }
    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->access_token === $authKey;
    }
    public function validateCurrentPassword($attribute, $params)
    {
        if (!$this->verifyPassword($this->old_password)) {
            $this->addError($attribute, 'Password Lama salah harap input kembali ');
        }
    }
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    public static function getStatus()
    {
        $status = [10 => 'Active', 0 => 'Non Active'];
        return $status;
    }

    public static function getUrlAvatar($model){
         if(empty($model->photo)){
            return Yii::getAlias("@web/public/site/default_avatar.jpg"); 
         }else{
            return Yii::getAlias("@web/public/users/").$model->photo;
         }
    }
}
