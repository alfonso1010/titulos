<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "carrera".
 *
 * @property string $cveCarrera
 * @property string $nombreCarrera
 * @property string|null $fechaInicio
 * @property string $fechaTerminacion
 * @property int $idAutorizacionReconocimiento
 * @property string $autorizacionReconocimiento
 * @property string|null $numeroRvoe
 * @property string $cveInstitucion
 *
 * @property Institucion $cveInstitucion0
 * @property Profesionista[] $profesionistas
 */
class Carrera extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'carrera';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cveCarrera', 'nombreCarrera', 'idAutorizacionReconocimiento', 'autorizacionReconocimiento', 'cveInstitucion'], 'required'],
            [['fechaInicio', 'fechaTerminacion'], 'safe'],
            [['idAutorizacionReconocimiento'], 'integer'],
            [['cveCarrera', 'cveInstitucion'], 'string', 'max' => 7],
            [['nombreCarrera', 'autorizacionReconocimiento', 'numeroRvoe'], 'string', 'max' => 100],
            [
                'cveCarrera',
                'unique',
                'targetAttribute' => ['cveCarrera', 'nombreCarrera','cveInstitucion'],
            ],
            [['cveInstitucion'], 'exist', 'skipOnError' => true, 'targetClass' => Institucion::className(), 'targetAttribute' => ['cveInstitucion' => 'cveInstitucion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cveCarrera' => 'Cve Carrera',
            'nombreCarrera' => 'Nombre Carrera',
            'fechaInicio' => 'Fecha Inicio',
            'fechaTerminacion' => 'Fecha Terminacion',
            'idAutorizacionReconocimiento' => 'Id Autorizacion Reconocimiento',
            'autorizacionReconocimiento' => 'Autorizacion Reconocimiento',
            'numeroRvoe' => 'Numero Rvoe',
            'cveInstitucion' => 'Cve Institucion',
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

    /**
     * Gets query for [[Profesionistas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfesionistas()
    {
        return $this->hasMany(Profesionista::className(), ['cveCarrera' => 'cveCarrera']);
    }
}
