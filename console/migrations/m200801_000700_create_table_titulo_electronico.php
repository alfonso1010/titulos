<?php

use yii\db\Migration;

/**
 * Class m200801_000700_create_table_titulo_electronico
 */
class m200801_000700_create_table_titulo_electronico extends Migration
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

        $this->createTable('titulo_electronico', [
            'idTituloElectronico' => $this->integer(11)->unsigned()->notNull()->append('AUTO_INCREMENT PRIMARY KEY'),
            'xmlns' => $this->string(100)->notNull()->defaultValue("https://www.siged.sep.gob.mx/titulos/"),
            'version' => $this->string(100)->notNull()->defaultValue("1.0"),
            'xmlnsXsi' => $this->string(250)->notNull()->defaultValue("http://www.w3.org/2001/XMLSchema-instance"),
            'xsiShecmaLocation' => $this->string(250)->notNull()->defaultValue("https://www.siged.sep.gob.mx/titulos/schema.xsd"),
        ], $tableOptions);

       
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200801_000700_create_table_titulo_electronico cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200801_000700_create_table_titulo_electronico cannot be reverted.\n";

        return false;
    }
    */
}
