<?php

use yii\db\Migration;

/**
 * Class m200731_235329_create_table_carrera
 */
class m200731_235329_create_table_carrera extends Migration
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
        $this->createTable('carrera', [
            'cveCarrera' => $this->string(7)->unsigned()->notNull()->append('PRIMARY KEY'),
            'nombreCarrera' => $this->string(100)->notNull()->unique(),
            'fechaInicio' => $this->date(),
            'fechaTerminacion' => $this->date()->notNull(),
            'idAutorizacionReconocimiento' => $this->integer(11)->notNull(),
            'autorizacionReconocimiento' => $this->string(100)->notNull(),
            'numeroRvoe' => $this->string(100),
            'cveInstitucion' => $this->string(7)->unsigned()->notNull(),
        ], $tableOptions);

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-carrera_institucion',
            'carrera',
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
        echo "m200731_235329_create_table_carrera cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200731_235329_create_table_carrera cannot be reverted.\n";

        return false;
    }
    */
}
