<?php

use yii\db\Migration;

/**
 * Class m200830_215611_create_table_titulos_ws
 */
class m200830_215611_create_table_titulos_ws extends Migration
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
        
        $this->createTable('titulos_ws', [
            'id' => $this->integer(11)->unsigned()->notNull()->append('AUTO_INCREMENT PRIMARY KEY'),
            'cveInstitucion' => $this->string(7)->unsigned()->notNull(),
            'nombre_archivo' => $this->string(255)->notNull(),
            'numero_lote' => $this->string(255)->notNull(),
            'mensaje' => $this->text(),
            'fecha_envio' => $this->dateTime()->notNull(),
            'ambiente' => $this->integer(11)->notNull()->defaultValue('0'),
        ], $tableOptions);

         // add foreign key for table `user`
        $this->addForeignKey(
            'fk-titulos_institucion',
            'titulos_ws',
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
        echo "m200830_215611_create_table_titulos_ws cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200830_215611_create_table_titulos_ws cannot be reverted.\n";

        return false;
    }
    */
}
