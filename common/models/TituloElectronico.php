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
            [['xmlns', 'version','xmlnsXsi','xsiShecmaLocation'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idTituloElectronico' => 'Id Titulo Electronico',
            'xmlns' => 'xmlns',
            'version' => 'Version',
            'xmlnsXsi' => 'xmlnsXsi',
            'xsiShecmaLocation' => 'xsiShecmaLocation',
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
