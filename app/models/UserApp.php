<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\components\Imagine;
use yii\web\UploadedFile;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $fullname
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserApp extends User
{
	const STATUS_DELETED = 0;
	const STATUS_ACTIVE = 10;
	public $new_password, $old_password, $repeat_password;
	/**
	 * @inheritdoc
	 */
	private $_user;
	protected $password;

	public static function tableName()
	{
		return 'user';
	}

	/**
	 * @inheritdoc
	 */
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

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
         [['username', 'email'], 'required'],
         [['new_password', "fullname"], 'required','on' => 'create'],
			[['username', 'email', 'password_hash', 'fullname'], 'string', 'max' => 255],
			[['username', 'email'], 'unique'],
			[['email'], 'email'],
			[['photo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'gif, png, jpg'],
			[['old_password'], 'validateCurrentPassword', 'on' => 'update'],
			[['old_password', 'new_password', 'repeat_password'], 'string', 'min' => 6],
			[['repeat_password'], 'compare', 'compareAttribute' => 'new_password'],
			[['old_password', 'new_password', 'repeat_password'], 'required', 'when' => function ($model) {
				return (!empty($model->new_password));
			}, 'whenClient' => "function (attribute, value) {
                return ($('#user-new_password').val().length>0);
            }", "on"=> "update"],
			//['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username has already been taken.'],
		];
	}

	public function scenarios()
	{
		$scenarios = parent::scenarios();
      // $scenarios['update'] = ['old_password', 'new_password', 'repeat_password'];
      // $scenarios['create'] = ['new_password'];

		return $scenarios;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'username' => 'Username',
			'password_hash' => 'Password Hash',
			'email' => 'Email',
			'old_password' => 'Password Lama',
			'new_password' => 'Password Baru',
			'repeat_password' => 'Ulangi Password',
		];
	}

   public function validateCurrentPassword($attribute, $params)
	{
		if (!$this->verifyPassword($this->old_password)) {
			$this->addError($attribute, 'Password sebelumnya tidak sesuai ');
		}
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRoles()
	{
		return $this->hasMany(AuthAssignment::className(), [
			'user_id' => 'id',
		]);
	}

	public static function getPath()
	{
		return Yii::getAlias('@webroot/public/users/');
	}

	public function upload()
	{
		if ($this->photo instanceof UploadedFile) {
			$path = self::getPath();
			$prefix = (!empty(Yii::$app->user->id)) ? 'USERID_' . Yii::$app->user->id : 'USERX';

			$path = $path . DIRECTORY_SEPARATOR . $prefix . DIRECTORY_SEPARATOR;
			// $namaFile=$this->img_avatar->name;
			$namaFile = 'photo' . Yii::$app->user->id . '.' . $this->photo->extension;
			// pull path berserta nama file dan extensionnya
			$fullPath = $path . $namaFile;
			if (!is_dir($path)) {
				mkdir($path, 0777);
			}

			if ($this->photo->saveAs($fullPath, false)):

			endif;
			$this->photo = '/' . $prefix . '/' . $namaFile;
			return TRUE;
		} else {
			$this->photo = $this->getOldAttribute('photo');
			return FALSE;

		}
	}
}

?>