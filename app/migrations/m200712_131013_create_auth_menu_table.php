<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%auth_menu}}`.
 */
class m200712_131013_create_auth_menu_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%auth_menu}}', [
            'id' => $this->primaryKey(),
            'menu_id' => $this->integer(),
            'role_name' => $this->string(150),
        ]);

        if ($this->db->getTableSchema("auth_menu", true) != null) {

            $this->addForeignKey(
                'fk_auth_menu_menu_id',
                'auth_menu',
                'menu_id',
                'm_menu',
                'id',
                'CASCADE'
            );
        }
        if ($this->db->getTableSchema("auth_item", true) != null) {
            $this->addForeignKey(
                'fk_auth_menu_role_name',
                'auth_menu',
                'role_name',
                'auth_item',
                'name',
                'CASCADE'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%auth_menu}}');
    }
}
