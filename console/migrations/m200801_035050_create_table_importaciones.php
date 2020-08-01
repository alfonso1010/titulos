<?php

use yii\db\Migration;

/**
 * Class m200801_035050_create_table_importaciones
 */
class m200801_035050_create_table_importaciones extends Migration
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
        
        $this->createTable('importaciones', [
            'id' => $this->integer(11)->unsigned()->notNull()->append('AUTO_INCREMENT PRIMARY KEY'),
            'ruta_archivo' => $this->string(255)->notNull(),
            'importado' => $this->integer(11)->defaultValue('0')->notNull(),
        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200801_035050_create_table_importaciones cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200801_035050_create_table_importaciones cannot be reverted.\n";

        return false;
    }
    */
}
