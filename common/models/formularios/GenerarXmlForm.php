<?php
namespace common\models\formularios;

use Yii;

class GenerarXmlForm extends \yii\base\DynamicModel
{

   
    public $archivo;
    public $id_importacion;
   

    public function rules() {
       return [
            [['archivo'], 'required'],
            [['id_importacion'], 'safe'],
            [['archivo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'xlsx'],
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