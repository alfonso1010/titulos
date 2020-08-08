<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "responsables".
 *
 * @property int $id
 * @property string $nombre
 * @property string $primerApellido
 * @property string|null $segundoApellido
 * @property string $curp
 * @property int $idCargo
 * @property string $cargo
 * @property string|null $abrTitulo
 * @property string|null $sello
 * @property string|null $certificadoResponsable
 * @property string $noCertificadoResponsable
 * @property string $cveInstitucion
 *
 * @property Institucion $cveInstitucion0
 */
class Responsables extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'responsables';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'primerApellido', 'curp', 'idCargo', 'cargo', 'noCertificadoResponsable', 'cveInstitucion'], 'required'],
            [['idCargo'], 'integer'],
            [['sello', 'certificadoResponsable'], 'string'],
            [['nombre', 'primerApellido', 'segundoApellido', 'curp', 'cargo', 'abrTitulo', 'noCertificadoResponsable'], 'string', 'max' => 80],
            [['cveInstitucion'], 'string', 'max' => 7],
            [['id'], 'unique'],
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
            'nombre' => 'Nombre',
            'primerApellido' => 'Primer Apellido',
            'segundoApellido' => 'Segundo Apellido',
            'curp' => 'Curp',
            'idCargo' => 'Id Cargo',
            'cargo' => 'Cargo',
            'abrTitulo' => 'Abr Titulo',
            'sello' => 'Sello',
            'certificadoResponsable' => 'Certificado Responsable',
            'noCertificadoResponsable' => 'No Certificado Responsable',
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
}
