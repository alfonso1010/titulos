<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "importaciones".
 *
 * @property int $id
 * @property string $ruta_archivo
 * @property int $importado
 */
class Importaciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'importaciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ruta_archivo'], 'required'],
            [['importado'], 'integer'],
            [['ruta_archivo'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ruta_archivo' => 'Ruta Archivo',
            'importado' => 'Importado',
        ];
    }
}
