<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "antecedente".
 *
 * @property int $idAntecedente
 * @property string $institucionProcedencia
 * @property int $idTipoEstudioAntecedente
 * @property string $tipoEstudioAntecedente
 * @property string $idEntidadFederativa
 * @property string $entidadFederativa
 * @property string|null $fechaInicio
 * @property string $fechaTerminacion
 * @property string|null $noCedula
 * @property string $folioControl
 * @property string $curpProfesionista
 *
 * @property Profesionista $curpProfesionista0
 */
class Antecedente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'antecedente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['institucionProcedencia', 'idTipoEstudioAntecedente', 'tipoEstudioAntecedente', 'idEntidadFederativa', 'entidadFederativa', 'fechaTerminacion', 'folioControl'], 'required'],
            [['idTipoEstudioAntecedente'], 'integer'],
            [['fechaInicio', 'fechaTerminacion'], 'safe'],
            [['institucionProcedencia', 'tipoEstudioAntecedente', 'idEntidadFederativa', 'entidadFederativa', 'noCedula','folioControl'], 'string', 'max' => 250],
            [['idAntecedente'], 'unique'],
            [['folioControl'], 'exist', 'skipOnError' => true, 'targetClass' => Profesionista::className(), 'targetAttribute' => ['folioControl' => 'folioControl']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idAntecedente' => 'Id Antecedente',
            'institucionProcedencia' => 'Institucion Procedencia',
            'idTipoEstudioAntecedente' => 'Id Tipo Estudio Antecedente',
            'tipoEstudioAntecedente' => 'Tipo Estudio Antecedente',
            'idEntidadFederativa' => 'Id Entidad Federativa',
            'entidadFederativa' => 'Entidad Federativa',
            'fechaInicio' => 'Fecha Inicio',
            'fechaTerminacion' => 'Fecha Terminacion',
            'noCedula' => 'No Cedula',
            'folioControl' => 'Folio Control',
        ];
    }

}
