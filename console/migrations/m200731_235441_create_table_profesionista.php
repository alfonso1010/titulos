<?php

use yii\db\Migration;

/**
 * Class m200731_235441_create_table_profesionista
 */
class m200731_235441_create_table_profesionista extends Migration
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
        
        $this->createTable('profesionista', [
            'curp' => $this->string(18)->unsigned()->notNull()->append('PRIMARY KEY'),
            'nombre' => $this->string(100)->notNull(),
            'primerApellido' => $this->string(100)->notNull(),
            'segundoApellido' => $this->string(100),
            'correoElectronico' => $this->string(100)->notNull(),
            'folioControl' => $this->string(100)->notNull(),
            'idExpedicion' => $this->integer(11),
            'cveCarrera' => $this->string(7)->unsigned()->notNull(),
        ], $tableOptions);

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-profesionista_carrera',
            'profesionista',
            'cveCarrera',
            'carrera', //tbl_foranea
            'cveCarrera', // pk tbl_foranea
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200731_235441_create_table_profesionista cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200731_235441_create_table_profesionista cannot be reverted.\n";

        return false;
    }
    */
}
