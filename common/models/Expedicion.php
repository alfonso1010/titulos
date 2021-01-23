<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "expedicion".
 *
 * @property int $idExpedicion
 * @property string $fechaExpedicion
 * @property int $idModalidadTitulacion
 * @property string $modalidadTitulacion
 * @property string|null $fechaExamenProfesional
 * @property string|null $fechaExencionExamenProfesional
 * @property int $cumplioServicioSocial
 * @property int $idFundamentoLegalServicioSocial
 * @property string $fundamentoLegalServicioSocial
 * @property string $idEntidadFederativa
 * @property string $entidadFederativa
 * @property string $curpProfesionista
 *
 * @property Profesionista $curpProfesionista0
 */
class Expedicion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expedicion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idExpedicion', 'fechaExpedicion', 'idModalidadTitulacion', 'modalidadTitulacion', 'cumplioServicioSocial', 'idFundamentoLegalServicioSocial', 'fundamentoLegalServicioSocial', 'idEntidadFederativa', 'entidadFederativa'], 'required'],
            [['idModalidadTitulacion', 'cumplioServicioSocial', 'idFundamentoLegalServicioSocial'], 'integer'],
            [['fechaExpedicion', 'fechaExamenProfesional', 'fechaExencionExamenProfesional'], 'safe'],
            [['modalidadTitulacion', 'fundamentoLegalServicioSocial', 'idEntidadFederativa', 'entidadFederativa'], 'string', 'max' => 255],
            [['idExpedicion'], 'string', 'max' => 255],
            [['idExpedicion'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idExpedicion' => 'Id Expedicion',
            'fechaExpedicion' => 'Fecha Expedicion',
            'idModalidadTitulacion' => 'Id Modalidad Titulacion',
            'modalidadTitulacion' => 'Modalidad Titulacion',
            'fechaExamenProfesional' => 'Fecha Examen Profesional',
            'fechaExencionExamenProfesional' => 'Fecha Exencion Examen Profesional',
            'cumplioServicioSocial' => 'Cumplio Servicio Social',
            'idFundamentoLegalServicioSocial' => 'Id Fundamento Legal Servicio Social',
            'fundamentoLegalServicioSocial' => 'Fundamento Legal Servicio Social',
            'idEntidadFederativa' => 'Id Entidad Federativa',
            'entidadFederativa' => 'Entidad Federativa',
        ];
    }

}
