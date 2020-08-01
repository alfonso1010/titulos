<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "titulo_electronico".
 *
 * @property int $idTituloElectronico
 * @property string $xlmns
 * @property string $version
 * @property string $folioControl
 * @property string $cveInstitucion
 * @property string $curpProfesionista
 *
 * @property Institucion $cveInstitucion0
 * @property Profesionista $curpProfesionista0
 */
class TituloElectronico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'titulo_electronico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['xlmns', 'version', 'folioControl', 'cveInstitucion', 'curpProfesionista'], 'required'],
            [['xlmns', 'version', 'folioControl'], 'string', 'max' => 100],
            [['cveInstitucion'], 'string', 'max' => 7],
            [['curpProfesionista'], 'string', 'max' => 18],
            [['cveInstitucion'], 'exist', 'skipOnError' => true, 'targetClass' => Institucion::className(), 'targetAttribute' => ['cveInstitucion' => 'cveInstitucion']],
            [['curpProfesionista'], 'exist', 'skipOnError' => true, 'targetClass' => Profesionista::className(), 'targetAttribute' => ['curpProfesionista' => 'curp']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idTituloElectronico' => 'Id Titulo Electronico',
            'xlmns' => 'Xlmns',
            'version' => 'Version',
            'folioControl' => 'Folio Control',
            'cveInstitucion' => 'Cve Institucion',
            'curpProfesionista' => 'Curp Profesionista',
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
     * Gets query for [[CurpProfesionista0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurpProfesionista0()
    {
        return $this->hasOne(Profesionista::className(), ['curp' => 'curpProfesionista']);
    }
}
