<?php

use yii\db\Migration;

/**
 * Class m181211_160017_create_table_setting
 */
class m181211_160017_create_table_setting extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('setting', [
            'id' => $this->primaryKey(),
            'key' => $this->string(),
            'name' => $this->string(),
            'description' => $this->string(),
            'value' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181211_160017_create_table_setting cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181211_160017_create_table_setting cannot be reverted.\n";

        return false;
    }
    */
}
