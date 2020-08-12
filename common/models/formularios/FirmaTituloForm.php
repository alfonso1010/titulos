<?php
namespace common\models\formularios;

use Yii;

class FirmaTituloForm extends \yii\base\DynamicModel
{

   
    public $alumnos;
    public $password1;
    public $archivo_cer1;
    public $archivo_key1;
    public $password2;
    public $archivo_cer2;
    public $archivo_key2;
   

    public function rules() {
       return [
            [['alumnos','password1','archivo_cer1','archivo_key1','password2','archivo_cer2','archivo_key2'], 'required'],
            [['archivo_cer1'], 'file', 'skipOnEmpty' => true, 'extensions' => 'cer'],
            [['archivo_key1'], 'file', 'skipOnEmpty' => true, 'extensions' => 'key'],
            [['archivo_cer2'], 'file', 'skipOnEmpty' => true, 'extensions' => 'cer'],
            [['archivo_key2'], 'file', 'skipOnEmpty' => true, 'extensions' => 'key'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'archivo' => 'Archivo'
        ];
    }

    
}