<?php

use yii\db\Migration;

/**
 * Class m200731_235500_create_table_antecedente
 */
class m200731_235500_create_table_antecedente extends Migration
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

        $this->createTable('antecedente', [
            'idAntecedente' => $this->integer(11)->unsigned()->notNull()->append('PRIMARY KEY'),
            'institucionProcedencia' => $this->string(100)->notNull(),
            'idTipoEstudioAntecedente' => $this->integer(11)->notNull(),
            'tipoEstudioAntecedente' => $this->string(100)->notNull(),
            'idEntidadFederativa' => $this->string(100)->notNull(),
            'entidadFederativa' => $this->string(100)->notNull(),
            'fechaInicio' => $this->date(),
            'fechaTerminacion' => $this->date()->notNull(),
            'noCedula' => $this->string(100),
            'folioControl' => $this->string(100)->notNull(),
            'curpProfesionista' => $this->string(18)->unsigned()->notNull(),
        ], $tableOptions);

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-antecedente_profesionista',
            'antecedente',
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
        echo "m200731_235500_create_table_antecedente cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200731_235500_create_table_antecedente cannot be reverted.\n";

        return false;
    }
    */
}
