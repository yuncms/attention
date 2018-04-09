<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `attention`.
 */
class m180409_021224_create_attention_table extends Migration
{
    /**
     * @var string The table name.
     */
    public $tableName = '{{%attentions}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /**
         * 用户关注表
         */
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned()->comment('Id'),
            'user_id' => $this->integer()->unsigned()->notNull()->comment('User Id'),
            'model_id' => $this->integer()->notNull()->comment('Model Id'),
            'model_class' => $this->string()->notNull()->comment('Model Class'),
            'created_at' => $this->integer()->unsigned()->notNull()->comment('Created At'),
            'updated_at' => $this->integer()->unsigned()->notNull()->comment('Updated At'),
        ], $tableOptions);

        $this->addForeignKey('attentions_fk_1', $this->tableName, 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->createIndex('attentions_index', $this->tableName, ['user_id', 'model_id', 'model_class'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
