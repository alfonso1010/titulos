<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "profesionista".
 *
 * @property string $curp
 * @property string $nombre
 * @property string $primerApellido
 * @property string|null $segundoApellido
 * @property string $correoElectronico
 * @property string $folioControl
 * @property int|null $idExpedicion
 * @property string $cveCarrera
 *
 * @property Antecedente[] $antecedentes
 * @property Expedicion[] $expedicions
 * @property Carrera $cveCarrera0
 * @property TituloElectronico[] $tituloElectronicos
 */
class Profesionista extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profesionista';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['curp', 'nombre', 'primerApellido', 'correoElectronico', 'folioControl', 'cveCarrera','fechaTerminacion'], 'required'],
            [['idExpedicion'], 'integer'],
            [['curp'], 'string', 'max' => 18],
            [['fechaInicio'], 'string'],
            [['nombre', 'primerApellido', 'segundoApellido', 'correoElectronico', 'folioControl'], 'string', 'max' => 100],
            [['cveCarrera'], 'string', 'max' => 7],
            [['curp'], 'unique'],
            [['cveCarrera'], 'exist', 'skipOnError' => true, 'targetClass' => Carrera::className(), 'targetAttribute' => ['cveCarrera' => 'cveCarrera']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'curp' => 'Curp',
            'nombre' => 'Nombre',
            'primerApellido' => 'Primer Apellido',
            'segundoApellido' => 'Segundo Apellido',
            'correoElectronico' => 'Correo Electronico',
            'folioControl' => 'Folio Control',
            'idExpedicion' => 'Id Expedicion',
            'cveCarrera' => 'Cve Carrera',
            'fechaInicio' => 'Fecha Inicio',
            'fechaTerminacion' => 'Fecha Terminacion',
        ];
    }

    /**
     * Gets query for [[Antecedentes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAntecedentes()
    {
        return $this->hasMany(Antecedente::className(), ['curpProfesionista' => 'curp']);
    }

    /**
     * Gets query for [[Expedicions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpedicions()
    {
        return $this->hasMany(Expedicion::className(), ['curpProfesionista' => 'curp']);
    }

    /**
     * Gets query for [[CveCarrera0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCveCarrera0()
    {
        return $this->hasOne(Carrera::className(), ['cveCarrera' => 'cveCarrera']);
    }

    /**
     * Gets query for [[TituloElectronicos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTituloElectronicos()
    {
        return $this->hasMany(TituloElectronico::className(), ['curpProfesionista' => 'curp']);
    }
}
