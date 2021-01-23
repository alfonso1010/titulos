<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "institucion".
 *
 * @property string $cveInstitucion
 * @property string $nombreInstitucion
 *
 * @property Carrera[] $carreras
 * @property Responsables[] $responsables
 * @property TituloElectronico[] $tituloElectronicos
 */
class Institucion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'institucion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cveInstitucion', 'nombreInstitucion'], 'required'],
            [['cveInstitucion'], 'string', 'max' => 7],
            [['nombreInstitucion'], 'string', 'max' => 150],
            [['nombreInstitucion'], 'unique'],
            [['cveInstitucion'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cveInstitucion' => 'Cve Institucion',
            'nombreInstitucion' => 'Nombre Institucion',
        ];
    }

    /**
     * Gets query for [[Carreras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarreras()
    {
        return $this->hasMany(Carrera::className(), ['cveInstitucion' => 'cveInstitucion']);
    }

    /**
     * Gets query for [[Responsables]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponsables()
    {
        return $this->hasMany(Responsables::className(), ['cveInstitucion' => 'cveInstitucion']);
    }

    /**
     * Gets query for [[TituloElectronicos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTituloElectronicos()
    {
        return $this->hasMany(TituloElectronico::className(), ['cveInstitucion' => 'cveInstitucion']);
    }
}
