<?php

use yii\db\Migration;

/**
 * Class m200731_235318_create_table_institucion
 */
class m200731_235318_create_table_institucion extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('institucion', [
            'cveInstitucion' => $this->string(7)->unsigned()->notNull()->append('PRIMARY KEY'),
            'nombreInstitucion' => $this->string(150)->notNull()->unique()
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200731_235318_create_table_institucion cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200731_235318_create_table_institucion cannot be reverted.\n";

        return false;
    }
    */
}
