<?php

use yii\db\Migration;

/**
 * Class m240824_134032_parameters
 */
class m240824_134032_parameters extends Migration
{
    private $_tableName = 'parameters';

    public function safeUp()
    {
        $this->createTable($this->_tableName, [
            'ID' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'type' => $this->integer(1)->notNull(),
            'icon' => $this->string(),
            'icon_original_name' => $this->string(),
            'icon_gray' => $this->string(),
            'icon_gray_original_name' => $this->string(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable($this->_tableName);
    }
}
