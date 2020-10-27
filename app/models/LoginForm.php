<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $verifyCode;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            [['verifyCode'], 'captcha', 'when' => function () {
                return $this->captchaNeeded;
            }],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Username / Email',
            'password' => 'Password',
        ];
    }
    public function getCaptchaNeeded()
    {
        return Yii::$app->session->get('_try_login', 0) > 3;
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError('username', 'Username/email incorrect.');
                Yii::$app->session->set('_try_login', Yii::$app->session->get('_try_login', 0) + 1);

            } else if (!$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Password incorrect.');
                Yii::$app->session->set('_try_login', Yii::$app->session->get('_try_login', 0) + 1);

            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {

            $user = $this->getUser();
            if (Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0)) {
                $user->last_login = date("Y-m-d H:i");
                $user->save(false);

                return $user;
            }
        }

        return null;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findByUsername($this->username);
        }

        if (!$this->_user) {
            $this->_user = User::findByEmail($this->username);
        }

        return $this->_user;
    }
}
