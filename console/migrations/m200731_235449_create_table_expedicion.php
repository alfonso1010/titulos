<?php

use yii\db\Migration;

/**
 * Class m200731_235449_create_table_expedicion
 */
class m200731_235449_create_table_expedicion extends Migration
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
             
        $this->createTable('expedicion', [
            'idExpedicion' => $this->integer(11)->unsigned()->notNull()->append('PRIMARY KEY'),
            'fechaExpedicion' => $this->date()->notNull(),
            'idModalidadTitulacion' => $this->integer(11)->notNull(),
            'modalidadTitulacion' => $this->string(100)->notNull(),
            'fechaExamenProfesional' => $this->date(),
            'fechaExencionExamenProfesional' => $this->date(),
            'cumplioServicioSocial' => $this->integer(11)->notNull(),
            'idFundamentoLegalServicioSocial' => $this->integer(11)->notNull(),
            'fundamentoLegalServicioSocial' => $this->string(100)->notNull(),
            'idEntidadFederativa' => $this->string(100)->notNull(),
            'entidadFederativa' => $this->string(100)->notNull(),
            'curpProfesionista' => $this->string(18)->unsigned()->notNull(),
        ], $tableOptions);

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-expedicion_profesionista',
            'expedicion',
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
        echo "m200731_235449_create_table_expedicion cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200731_235449_create_table_expedicion cannot be reverted.\n";

        return false;
    }
    */
}
