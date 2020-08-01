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
            'xlmns' => $this->string(100)->notNull(),
            'version' => $this->string(100)->notNull(),
            'folioControl' => $this->string(100)->notNull(),
            'cveInstitucion' => $this->string(7)->unsigned()->notNull(),
            'curpProfesionista' => $this->string(18)->unsigned()->notNull(),
        ], $tableOptions);

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-titulo_electronico_institucion',
            'titulo_electronico',
            'cveInstitucion',
            'institucion', //tbl_foranea
            'cveInstitucion', // pk tbl_foranea
            'CASCADE'
        );

         $this->addForeignKey(
            'fk-titulo_electronico_profesionista',
            'titulo_electronico',
            'curpProfesionista',
            'profesionista', //tbl_foranea
            'curp', // pk tbl_foranea
            'CASCADE'
        );
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
