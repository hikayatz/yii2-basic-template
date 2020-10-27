<?php

use app\models\User;
use yii\db\Migration;

/**
 * Class m200712_092503_create_user_dummy
 */
class m200712_092503_create_user_dummy extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$user = new User([
			"username" => 'dzas42@gmail.com',
			"email" => 'dzas42@gmail.com',
			"fullname" => 'hikayat',
			"status" => User::STATUS_ACTIVE,
		]);
		$user->setPassword("secret");
		$user->save(false);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		echo "m200712_092503_create_user_dummy cannot be reverted.\n";

		return false;
	}

	/*
		    // Use up()/down() to run migration code without a transaction.
		    public function up()
		    {

		    }

		    public function down()
		    {
		        echo "m200712_092503_create_user_dummy cannot be reverted.\n";

		        return false;
		    }
	*/
}
