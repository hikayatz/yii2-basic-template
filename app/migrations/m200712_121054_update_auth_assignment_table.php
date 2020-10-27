<?php

use yii\db\Migration;

/**
 * Class m200712_121054_update_auth_assignment_table
 */
class m200712_121054_update_auth_assignment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        if ($this->db->getTableSchema("auth_assignment", true) != null) {

            if ($this->db->driverName === 'pgsql') {
                $this->db->createCommand("ALTER TABLE auth_assignment ALTER COLUMN user_id TYPE integer USING user_id::integer")->execute();
            } else {
                $this->alterColumn('auth_assignment', 'user_id', $this->integer(), 'DROP NOT NULL');
            }

            $this->addForeignKey(
                'fk_auth_assignment_user_id',
                'auth_assignment',
                'user_id',
                'user',
                'id',
                'CASCADE'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200712_121054_update_auth_assignment_table cannot be reverted.\n";

        return false;
    }

    /*
// Use up()/down() to run migration code without a transaction.
public function up()
{

}

public function down()
{
echo "m200712_121054_update_auth_assignment_table cannot be reverted.\n";

return false;
}
 */
}
