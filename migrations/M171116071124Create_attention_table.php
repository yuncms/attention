<?php

namespace yuncms\attention\migrations;

use yii\db\Migration;

class M171116071124Create_attention_table extends Migration
{

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
        $this->createTable('{{%attentions}}', [
            'id' => $this->primaryKey()->unsigned()->comment('Id'),
            'user_id' => $this->integer()->unsigned()->notNull()->comment('User Id'),
            'model_id' => $this->integer()->notNull()->comment('Model Id'),
            'model_class' => $this->string()->notNull()->comment('Model Class'),
            'created_at' => $this->integer()->unsigned()->notNull()->comment('Created At'),
            'updated_at' => $this->integer()->unsigned()->notNull()->comment('Updated At'),
        ], $tableOptions);

        $this->addForeignKey('{{%attentions_ibfk_1}}', '{{%attentions}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->createIndex('attentions_index', '{{%attentions}}', ['user_id', 'model_id', 'model_class'], true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%test}}');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M171116071124Create_attention_table cannot be reverted.\n";

        return false;
    }
    */
}
