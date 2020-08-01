<?php

use yii\db\Migration;

/**
 * Class m200731_235410_create_table_responsables
 */
class m200731_235410_create_table_responsables extends Migration
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

        
        $this->createTable('responsables', [
            'id' => $this->integer(11)->unsigned()->notNull()->append('PRIMARY KEY'),
            'nombre' => $this->string(80)->notNull(),
            'primerApellido' => $this->string(80)->notNull(),
            'segundoApellido' => $this->string(80),
            'curp' => $this->string(80)->notNull(),
            'idCargo' => $this->integer(11)->notNull(),
            'cargo' => $this->string(80)->notNull(),
            'abrTitulo' => $this->string(80),
            'sello' => $this->text(),
            'certificadoResponsable' => $this->text(),
            'noCertificadoResponsable' => $this->string(80)->notNull(),
            'cveInstitucion' => $this->string(7)->unsigned()->notNull()
        ], $tableOptions);

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-responsable_institucion',
            'responsables',
            'cveInstitucion',
            'institucion', //tbl_foranea
            'cveInstitucion', // pk tbl_foranea
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200731_235410_create_table_responsables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200731_235410_create_table_responsables cannot be reverted.\n";

        return false;
    }
    */
}
