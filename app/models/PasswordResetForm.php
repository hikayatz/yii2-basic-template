<?php
namespace app\models\user;

use app\models\user\User;
use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;

/**
 * Password reset form
 */
class PasswordResetForm extends Model {
	public $password;

	/**
	 * @var \common\models\User
	 */
	private $_user;

	/**
	 * Creates a form model given a token.
	 *
	 * @param  string                          $token
	 * @param  array                           $config name-value pairs that will be used to initialize the object properties
	 * @throws \yii\base\InvalidParamException if token is empty or not valid
	 */
	public function __construct($token, $config = []) {
		if (empty($token) || !is_string($token)) {
			throw new InvalidParamException('Password reset token tidak boleh kosong.');
		}
		$this->_user = User::findByPasswordResetToken($token);
		if (!$this->_user) {
			// throw new InvalidParamException('Reset Token password salah.');
			throw new \yii\base\UserException('Reset Token password salah.');
		}
		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['password', 'required', 'message' => '{attribute} harap diisi'],
			['password', 'string', 'min' => 6],
		];
	}

	/**
	 * Resets password.
	 *
	 * @return boolean if password was reset.
	 */
	public function resetPassword() {
		$user = $this->_user;
		$user->password = $this->password;
		$user->removePasswordResetToken();

		return $user->save();
	}
}
