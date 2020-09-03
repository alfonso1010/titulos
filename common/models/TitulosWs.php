<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "titulos_ws".
 *
 * @property int $id
 * @property string $cveInstitucion
 * @property string $nombre_archivo
 * @property string $numero_lote
 * @property string|null $mensaje
 * @property string $fecha_envio
 * @property int $ambiente
 *
 * @property Institucion $cveInstitucion0
 */
class TitulosWs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'titulos_ws';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cveInstitucion', 'nombre_archivo', 'numero_lote', 'fecha_envio'], 'required'],
            [['mensaje'], 'string'],
            [['fecha_envio'], 'safe'],
            [['ambiente'], 'integer'],
            [['cveInstitucion'], 'string', 'max' => 7],
            [['nombre_archivo', 'numero_lote'], 'string', 'max' => 255],
            [['cveInstitucion'], 'exist', 'skipOnError' => true, 'targetClass' => Institucion::className(), 'targetAttribute' => ['cveInstitucion' => 'cveInstitucion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cveInstitucion' => 'Cve Institucion',
            'nombre_archivo' => 'Nombre Archivo',
            'numero_lote' => 'Numero Lote',
            'mensaje' => 'Mensaje',
            'fecha_envio' => 'Fecha Envio',
            'ambiente' => 'Ambiente',
        ];
    }

    /**
     * Gets query for [[CveInstitucion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCveInstitucion0()
    {
        return $this->hasOne(Institucion::className(), ['cveInstitucion' => 'cveInstitucion']);
    }
}
