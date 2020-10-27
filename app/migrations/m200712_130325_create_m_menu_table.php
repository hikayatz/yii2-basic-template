<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%m_menu}}`.
 */
class m200712_130325_create_m_menu_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('{{%m_menu}}', [
			'id' => $this->primaryKey(),
			'parent_id' => $this->integer(),
			'title' => $this->string(100),
			'url' => $this->string(150),
			'description' => $this->string(),
			'icon' => $this->string(100),
			'menu_level' => $this->integer(),
			'menu_order' => $this->double(),
			'menu_status' => $this->integer(),
			'updated_at' => $this->timestamp(),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('{{%m_menu}}');
	}
}
