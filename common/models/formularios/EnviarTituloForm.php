<?php
namespace common\models\formularios;

use Yii;

class EnviarTituloForm extends \yii\base\DynamicModel
{

   
    public $institucion;
    public $usuario_ws;
    public $password_ws;
    public $nombre_archivo;
    public $ambiente;
    public $titulo;
   

    public function rules() {
       return [
            [['titulo','institucion','usuario_ws','password_ws','nombre_archivo','ambiente'], 'required'],
            [['titulo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'zip,xml'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'titulo' => 'Título'
        ];
    }

    
}