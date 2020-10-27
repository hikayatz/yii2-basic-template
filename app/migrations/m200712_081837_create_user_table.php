<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m200712_081837_create_user_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('{{%user}}', [
			'id' => $this->primaryKey(),
			'fullname' => $this->string(150),
			'username' => $this->string(150)->notNull()->unique(),
			'email' => $this->string(150)->notNull()->unique(),
			'password_hash' => $this->string()->notNull(),
			'password_reset_token' => $this->string(),
			'access_token' => $this->string(),
			'photo' => $this->string(),
			'status' => $this->smallInteger(),
			'last_login' => $this->timestamp(),
			'created_at' => $this->timestamp()->defaultValue('NOW()'),
			'updated_at' => $this->timestamp()->defaultValue('NOW()'),

		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('{{%user}}');
	}
}
